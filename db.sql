-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- https://github.com/Spenzert/CoinFaucet

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `CoinFaucet`
--

-- --------------------------------------------------------

--
-- Table structure for table `ip_table`
--

CREATE TABLE `ip_table` (
  `ip` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `access_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

