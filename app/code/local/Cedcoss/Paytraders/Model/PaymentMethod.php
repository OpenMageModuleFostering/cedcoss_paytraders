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
class Cedcoss_Paytraders_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract
{
    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = 'paytraders';
	

	//to show the form of the payment method form on checkout
	protected $_formBlockType = 'paytraders/form_paytraders';

	
	//to show the different block info on sales order view page
	protected $_infoBlockType = 'paytraders/info_paytraders';
 
    /**
     * Here are examples of flags that will determine functionality availability
     * of this module to be used by frontend and backend.
     *
     * @see all flags and their defaults in Mage_Payment_Model_Method_Abstract
     *
     * It is possible to have a custom dynamic logic by overloading
     * public function can* for each flag respectively
     */
     
    /**
     * Is this payment method a gateway (online auth/charge) ?
     */
    protected $_isGateway               = true;
 
    /**
     * Can authorize online?
     */
    protected $_canAuthorize            = true;
 
    /**
     * Can capture funds online?
     */
    protected $_canCapture              = true;
 
    /**
     * Can capture partial amounts online?
     */
    protected $_canCapturePartial       = false;
 
    /**
     * Can refund online?
     */
    protected $_canRefund               = false;
 
    /**
     * Can void transactions online?
     */
    protected $_canVoid                 = true;
 
    /**
     * Can use this payment method in administration panel?
     */
    protected $_canUseInternal          = true;
 
    /**
     * Can show this payment method as an option on checkout payment page?
     */
    protected $_canUseCheckout          = true;


	
    /**
     * Is this payment method suitable for multi-shipping checkout?
     */
    protected $_canUseForMultishipping  = true;
 
    /**
     * Can save credit card information for future processing?
     */
    protected $_canSaveCc = false;
 
    /**
     * Here you will need to implement authorize, capture and void public methods
     *
     * @see examples of transaction specific public methods such as
     * authorize, capture and void in Mage_Paygate_Model_Authorizenet
     */
	 
	 
/**
* Return Order place redirect url
*
* @return string
*/ 

/* public function getCheckoutRedirectUrl()
    {
return Mage::getUrl('paytraders/standard/redirect', array('_secure' => true));
    }*/
	
	
	public function getOrderPlaceRedirectUrl()
	{

		return Mage::getUrl('paytraders/standard/redirect', array('_secure' => true));

	}
	
	
	/**
     * Check whether payment method can be used
     * @param Mage_Sales_Model_Quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if ($status = parent::isAvailable($quote)) {
			$showForce = Mage::getStoreConfig('payment/paytraders/showforce_paymentmethod');
			$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
			$flag = false;
			if($currency_code != 'GBP' && $showForce)
				$flag = true;
			else if($currency_code == 'GBP')	
				$flag = true;
			else if($currency_code != 'GBP' && (!$showForce))
				$flag = false;

			return $flag;
		}
	}
	
	/**
     * Validate payment method information object
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function validate()
    {
         /**
          * to validate payment method is allowed for billing country or not
          */
         parent::validate();
		 
	 	$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
		 
	    if ($currency_code!='GBP') {
             Mage::throwException(Mage::helper('paytraders')->__('Paya Traders payment method support with GBP currency.'));
         }
         return $this;
    }
	


}
?>