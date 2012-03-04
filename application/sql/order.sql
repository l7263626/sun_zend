-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Feb 07, 2012, 07:04 AM
-- 伺服器版本: 5.0.51
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 資料庫: `sun`
-- 

-- --------------------------------------------------------

-- 
-- 資料表格式： `order`
-- 

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `in_date` date NOT NULL,
  `in_mileage` int(11) NOT NULL,
  `out_date` date default NULL,
  `original_charge` int(10) unsigned default NULL,
  `real_charge` int(10) unsigned default NULL,
  `discount` float default NULL,
  `comment` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
