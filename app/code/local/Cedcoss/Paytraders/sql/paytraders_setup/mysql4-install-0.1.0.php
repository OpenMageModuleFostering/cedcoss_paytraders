<?php
// here are the table creation for this module e.g.:
$installer = $this;
$installer->startSetup();
$this->run("CREATE TABLE IF NOT EXISTS `{$installer->getTable('paytraders/transaction')}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `amount` float NOT NULL,
  `transaction_status` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `transaction_value_pence` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `order_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;");
$this->endSetup();