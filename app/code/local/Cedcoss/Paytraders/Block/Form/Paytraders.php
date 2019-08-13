<?php
/**
 * Lay-Buys
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
 * @package     Cedcoss_Paytrader
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block for LayBuy payment method form
 */
class Cedcoss_Paytraders_Block_Form_Paytraders extends Mage_Payment_Block_Form
{
	protected $_storeId = null;
	
	public function getStoreId(){
		if(empty($this->_storeId)){
			$this->_storeId =  Mage::app()->getStore()->getId();	
		}
		return $this->_storeId;	
	}
	
	/**
     * Block construction. Set block template.
     */
    protected function _construct()
    {
        parent::_construct();
		
		/*$paytraderMark = Mage::getConfig()->getBlockClassName('core/template');
        $paytraderMark = new $paytraderMark;
        $paytraderMark->setTemplate('paytraders/form/paytraders.phtml')
			 ->setPaytradersTitle(Mage::helper('paytraders')->__('Paytaraders Payment Solution!'))
			 ->setPaymentAcceptanceMarkSrc('http://lay-buys.com/gateway/LAY-BUY.png')
			 ->setPaymentAcceptanceMarkHref('http://www.payatrader.com/');*/
			 
			
		$image = Mage::getStoreConfig('payment/paytraders/image');
	
			
		$laybuyMark = Mage::getConfig()->getBlockClassName('core/template');
        $laybuyMark = new $laybuyMark;
        $laybuyMark->setTemplate('paytraders/form/paytraders.phtml')
			 ->setLayBuyTitle(Mage::helper('paytraders')->__('Paytaraders Payment Solution!'));
		
		if($image){
			$image = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."payatrader/".$image;
			$laybuyMark->setPaymentAcceptanceMarkSrc($image);
		}	 
			$laybuyMark->setPaymentAcceptanceMarkHref('http://www.payatrader.com/');


		if($image){
			$this->setMethodTitle('')->setMethodLabelAfterHtml($laybuyMark->toHtml());
		}else{
			$this->setMethodLabelAfterHtml($laybuyMark->toHtml());
		}

		
    }
	
	public function getArray($type){
		$totals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
		$grandtotal = round($totals["grand_total"]->getValue());
		return Mage::getModel('laybuy/report')->getArray($type,$grandtotal,$this->getStoreId());
	}
	
	

}