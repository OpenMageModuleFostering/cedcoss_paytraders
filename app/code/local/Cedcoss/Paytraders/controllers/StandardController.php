<?php
/**
 * Pay a trader
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Cedcoss
 * @package     Cedcoss_Paytraders
 * @author 		Magento Team<magentoteam@cedcoss.com>
 * @copyright   Copyright Payatraders (2011). (https://www.payatrader.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * PayaTrader Report model
 */
 
class Cedcoss_Paytraders_StandardController extends Mage_Core_Controller_Front_Action
{
	  /**
     * Get one page checkout model
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

	
	

    /**
     * When a customer chooses Paytraders on Checkout/Payment page
     * 
     */
    public function redirectAction()
    { 
	    $session = Mage::getSingleton('checkout/session');
		$session->setPaytraderStandardQuoteId($session->getQuoteId());
		$this->getResponse()->setBody($this->getLayout()->createBlock('Cedcoss_Paytraders_Block_Standard_Redirect')->toHtml());
		$session->unsQuoteId();
        $session->unsRedirectUrl();
    }

	 /**
     * When a customer chooses Paytraders on Checkout/Posturl page
     * Will update all order
     */
	 public function posturlAction()
    { 
		$data = $this->getRequest()->getParams();
		if($data && isset($data['transaction_status'])){
			
		if(isset($data['order_number']))
			$order_id = $data['order_number'];
			
			
			$order = Mage::getModel('sales/order')->load($order_id, 'increment_id');
			$amount = $order->getData('base_grand_total');
			$transaction = Mage::getModel('paytraders/transaction');
	
			if(isset($data['transaction_status']))
				$transaction->setdata('transaction_status', $data['transaction_status']);
				
			if(isset($data['transaction_value_pence']))
				$transaction->setdata('transaction_value_pence', $data['transaction_value_pence']);
			
			if(isset($data['order_number']))
				$transaction->setdata('order_number', $data['order_number']);
				
			if(isset($data['date_time']))	
				$transaction->setdata('date_time', $data['date_time']);
				
				
			$transaction->setdata('amount', $amount);
			$transaction->save();
	
			$order->sendNewOrderEmail();
			if( isset($data['transaction_status']) && $data['transaction_status'] == 'D'){
				 $order->cancel()->save();
				 Mage::getSingleton('core/session')->addError("Your Order has been cancelled due to payement Declined!!");
	 			$order->sendOrderUpdateEmail();

			}
			

		}
		

	}



    /**
     * When a customer cancel payment from payatrader.
     */
    public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getReddotStandardQuoteId(true));
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * when paytrader returns
     * The order information at this point is in POST
     * variables.  However, you don't want to "process" the order until you
     */
    public function  newsuccessAction()
    {
		$ono =  $this->getRequest()->getParam('ono');
		$collection = Mage::getModel('paytraders/transaction')->getCollection();
		$collection->addFieldToFilter('order_number', $ono);
		$status = $collection->getFirstItem()->getTransactionStatus();
		//for returning when direct access
		 $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }
        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }
		if($status == 'D'){
			$this->loadLayout();
			$this->getLayout()->getBlock('head')->setTitle($this->__('Paytraders Notification'));
			$this->renderLayout();
		}else{
        	$this->_redirect('checkout/onepage/success');
		}
    }
}