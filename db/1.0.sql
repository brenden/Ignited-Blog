-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2010 at 07:05 AM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `projects`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` VALUES(1, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `blogid` int(11) NOT NULL,
  `author` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `website` varchar(256) DEFAULT NULL,
  `ip` varchar(256) NOT NULL,
  `date` varchar(256) NOT NULL,
  `body` text,
  `markdown` text,
  `flagged` varchar(20) DEFAULT 'false',
  PRIMARY KEY (`id`),
  KEY `postid` (`postid`),
  KEY `flagged` (`flagged`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE `flags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commentid` int(11) NOT NULL,
  `ip` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `commentid` (`commentid`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blogid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `markdown` text NOT NULL,
  `date` varchar(256) NOT NULL,
  `tags` varchar(256) DEFAULT 'untagged',
  `commentlock` varchar(256) DEFAULT 'open',
  `deleted` varchar(256) DEFAULT 'false',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `tags` (`tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `firstname` varchar(256) DEFAULT NULL,
  `lastname` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `lastlogin` varchar(256) DEFAULT NULL,
  `blogid` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
