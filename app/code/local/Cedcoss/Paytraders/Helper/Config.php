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
 
class Cedcoss_Paytraders_Helper_Config extends Mage_Core_Helper_Abstract{
	/*
	 * Get the gateway submit url
	 * @author magentoteam@cedcoss.com
	 */
	public function getSubmitUrl(){
		return Mage::getStoreConfig('payment/paytraders/submit_url');
	}
	
	/*
	 * For form filed At Reddot gateway
	 * @author magentoteam@cedcoss.com
	 */
	public function getStandardCheckoutFormFields($data){
		return $data;
	}
	
	/*
	 * For form filed At Reddot gateway
	 * @author magentoteam@cedcoss.com

	 */
	public function extractAndPrepareRequiredValueForFormFields($chekoutSession){
		$checkout = Mage::getSingleton('checkout/session')->getQuote();
		$orderId = $chekoutSession->getLastRealOrderId();
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
		$billing = $order->getBillingAddress();
		$amount = $order->getData('grand_total');
		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		$email = $order->getData('customer_email');
		$site_code = Mage::getStoreConfig('payment/paytraders/site_code');
		$site_url =  Mage::getBaseUrl();
		$posturl  = Mage::getUrl('paytraders/standard/posturl');
		$returnurl = Mage::getUrl('paytraders/standard/newsuccess');
		$traderdisplayname = Mage::getStoreConfig('payment/paytraders/traderdisplayname');
		$email_alert = Mage::getStoreConfig('payment/paytraders/email_alert');
		if($email_alert ==1 )
			$email_alert	= 'y';
		else	
			$email_alert	= 'n';

		$firstname = $billing->getData('firstname');
		$lastname = $billing->getData('lastname');
		$street = $billing->getData('street');
		$city = $billing->getData('city');
		$state = $billing->getData('region');
		$postalcode = $billing->getData('postcode');
		$country = $billing->getData('country_id');
		$phone = $billing->getData('telephone');
		$payment =  $order->getPayment();
		$data = array(
					'site_code'=>$site_code,
					'site_url'=>$site_url,
					'posturl'=>$posturl,
					'returnurl' => $returnurl,
					'traderdisplayname' => $traderdisplayname,
					'customer_name' 	=> $firstname . ' '. $lastname,
					'customer_email' => $email,
					'customer_telephone' => $phone,
					'customer_postcode' => $postalcode,
					'customer_house_name_or_number' => $street.' , '.$city.' , '.$state.' , '.$country,
					'transaction_value_pence' => $amount*100,
					'order_number'	 => 	$orderId,
					'email_alert'	 => 	$email_alert,
					'cart_type'		 => 'magento',
					'cart_version'   => Mage::getVersion(),
					'module_version' => Mage::getConfig()->getModuleConfig('Cedcoss_Paytraders')->version
					);
		return $data;
	}
	
	
}