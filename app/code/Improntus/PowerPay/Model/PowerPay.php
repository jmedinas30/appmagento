<?php

namespace Improntus\PowerPay\Model;

use Improntus\PowerPay\Model\Rest\WebService;
use Improntus\PowerPay\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\InvoiceManagementInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\TransactionRepositoryInterface as PaymentTransactionRepository;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Improntus\PowerPay\Api\TransactionRepositoryInterface;

class PowerPay
{
    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;
    /**
     * @var OrderSender
     */
    private $orderSender;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;
    /**
     * @var PaymentTransactionRepository
     */
    private $paymentTransactionRepository;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var OrderPaymentRepositoryInterface
     */
    private $paymentRepository;
    /**
     * @var InvoiceManagementInterface
     */
    private $invoiceManagement;

    /**
     * @var WebService
     */
    private $ws;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        WebService                      $ws,
        Data                            $helper,
        InvoiceManagementInterface      $invoiceManagement,
        OrderPaymentRepositoryInterface $paymentRepository,
        OrderRepositoryInterface        $orderRepository,
        PaymentTransactionRepository    $paymentTransactionRepository,
        InvoiceRepositoryInterface      $invoiceRepository,
        OrderSender                     $orderSender,
        TransactionRepositoryInterface $transactionRepository,
        TransactionFactory $transactionFactory
    )
    {
        $this->transactionFactory = $transactionFactory;
        $this->transactionRepository = $transactionRepository;
        $this->orderSender = $orderSender;
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentTransactionRepository = $paymentTransactionRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
        $this->invoiceManagement = $invoiceManagement;
        $this->helper = $helper;
        $this->ws = $ws;
    }

    /**
     * @param $order
     * @return false|mixed|string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function createTransaction($order)
    {
        $data = $this->getRequestData($order);
        try {
            $response = $this->ws->doRequest($this->helper::EP_MERCHANT_TRANSACTIONS, $this->helper->getSecret(), $data);
        } catch (\Exception $e) {
            $this->helper->log($e->getMessage());
            throw new \Exception($e->getMessage());
        }
        return $response ?? false;
    }

    /**
     * @param Order $order
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getRequestData($order)
    {
        $token = $this->helper->generateToken($order);
        $customerData = $this->getCustomerData($order);
        return [
            'external_id' => $order->getIncrementId(),
            'callback_url' => $this->helper->getCallBackUrl($token),
            'values' => [
                'merchant_id' => $this->helper->getMerchantId($order->getStoreId()),
                'currency' => 'PEN',
                'document_number' => $customerData['document_number'],
                'document_type' => 'DNI',
                'first_name' => $customerData['first_name'],
                'last_name' => $customerData['last_name'],
                'email' => $customerData['email'],
                'country_code' => '+51',
                'phone_number' => $customerData['phone_number'],
                'payment_concept' => $this->helper->getPaymentConcept($order->getStoreId()),
                'shipping_postal_code' => $customerData['shipping_postal_code'],
                'shipping_address' => $customerData['shipping_address'],
            ],
            'amount' => round($order->getGrandTotal(), 2)
        ];
    }

    /**
     * @param Order $order
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getCustomerData($order)
    {
        $address = $order->getBillingAddress();
        return [
            'document_number' => $order->getCustomerTaxvat() ?? '',
            'first_name' => $address->getFirstname(),
            'last_name' => $address->getLastname(),
            'email' => $order->getCustomerEmail(),
            'phone_number' => $address->getTelephone() ?? '',
            'shipping_postal_code' => $address->getPostcode() ?? '',
            'shipping_address' => "{$address->getStreetLine(1)} {$address->getStreetLine(2)} {$address->getStreetLine(3)} {$address->getStreetLine(4)}",
        ];
    }

    /**
     * @param $order
     * @param $transactionId
     * @return bool
     */
    public function invoice($order, $transactionId)
    {
        if (!$order->canInvoice() || $order->hasInvoices()) {
            return false;
        }
        try {
            $invoice = $this->invoiceManagement->prepareInvoice($order);
            $invoice->register();
            $this->orderRepository->save($order);
            $invoice->setTransactionId($transactionId);
            $payment = $order->getPayment();
            $this->paymentRepository->save($payment);
            $transaction = $this->generateTransaction($payment, $invoice, $transactionId);
            $transaction->setAdditionalInformation('amount', round($order->getGrandTotal(), 2));
            $transaction->setAdditionalInformation('currency', 'PEN');
            $this->paymentTransactionRepository->save($transaction);

            if (!$order->getEmailSent()) {
                $this->orderSender->send($order);
                $order->setIsCustomerNotified(true);
            }
            $invoice->pay();
            $invoice->getOrder()->setIsInProcess(true);
            $payment->addTransactionCommentsToOrder($transaction, __('Powerpay'));
            $this->invoiceRepository->save($invoice);
            $message = (__('Payment confirmed by PowerPay'));
            $order->addCommentToStatusHistory($message, Order::STATE_PROCESSING);
            $this->orderRepository->save($order);
            $ppTransaction = $this->transactionRepository->get($transactionId);
            $ppTransaction->setStatus('processed');
            $this->transactionRepository->save($ppTransaction);

            return true;
        } catch (\Exception $e) {
            $this->helper->log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $payment
     * @param $invoice
     * @param $paypalTransaction
     * @return mixed
     */
    private function generateTransaction($payment, $invoice, $transactionId)
    {
        $payment->setTransactionId($transactionId);
        return $payment->addTransaction(TransactionInterface::TYPE_CAPTURE, $invoice, true);
    }


    /**
     * @param $order
     * @param $result
     * @return void
     * @throws LocalizedException
     */
    public function persistTransaction($order, $result, $flow = 'response')
    {
        try {
            if ($flow !== 'response') {
                $transactionId = $result['id'];
            } else {
                $transactionId = $result['transaction_id'];
            }
            $status = strtolower($result['status'] ?? '');
            if (!$this->transactionRepository->getByOrderId($order->getId())) {
                $transaction = $this->transactionFactory->create();
                $transaction->setOrderId($order->getId());
                $transaction->setPowerPayTransactionId($transactionId ?? '');
                $transaction->setStatus($status);
                if (isset($result['created_at'])) {
                    $transaction->setCreatedAt($result['created_at']);
                }
                $transaction->setExpiredAt($result['expired_at'] ?? '');
                $this->transactionRepository->save($transaction);
            } else {
                $transaction = $this->transactionRepository->get($transactionId);
                $transaction->setStatus($status);
                $transaction->setExpiredAt($result['expired_at'] ?? '');
                $this->transactionRepository->save($transaction);
            }
        } catch (\Exception $e) {
            $this->helper->log($e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @param Phrase $message
     * @return bool
     */
    public function cancelOrder($order, $message)
    {
        try {
            if ($order->canCancel()) {
                $order->cancel();
                $order->setState(Order::STATE_CANCELED);
                $order->addCommentToStatusHistory($message, Order::STATE_CANCELED);
                $this->orderRepository->save($order);
                return true;
            }
        } catch (\Exception $e) {
            $this->helper->log($e->getMessage());
            return false;
        }
        return false;
    }

    /**
     * @param $id
     * @return false|\Magento\Sales\Api\Data\OrderInterface
     * @throws LocalizedException
     */
    public function getOrderByTransactionId($id)
    {
        $transaction = $this->transactionRepository->get($id);
        if ($transaction->getStatus()) {
            return $this->orderRepository->get($transaction->getOrderId());
        }
        return false;
    }

    /**
     * @param $id
     * @return false|\Improntus\PowerPay\Api\Data\TransactionInterface
     */
    public function checkIfExists($id)
    {
        try {
            return $this->transactionRepository->get($id);
        } catch (\Exception $e) {
            $this->helper->log($e->getMessage());
            return false;
        }
    }

    /**
     * @param OrderInterface $order
     * @return void
     */
    public function addSuccessToStatusHistory($order)
    {
        if ($order->getState() === Order::STATE_NEW) {
            $message = (__('Payment confirmed by PowerPay, awaiting capture.'));
            $order->addCommentToStatusHistory($message, Order::STATE_PAYMENT_REVIEW);
            $this->orderRepository->save($order);
        }
    }
}
