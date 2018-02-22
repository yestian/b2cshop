# Host: localhost  (Version: 5.5.40-log)
# Date: 2018-02-22 19:57:49
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "tp_article"
#

DROP TABLE IF EXISTS `tp_article`;
CREATE TABLE `tp_article` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `keywords` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `author` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `link_url` varchar(128) DEFAULT NULL COMMENT '设置了外链，就会跳转',
  `thumb` varchar(128) DEFAULT NULL,
  `content` longtext,
  `cate_id` smallint(5) unsigned DEFAULT NULL,
  `show_top` tinyint(1) unsigned DEFAULT '0' COMMENT '置顶',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '默认显示',
  `sort` smallint(5) unsigned DEFAULT '50' COMMENT '排序',
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

#
# Data for table "tp_article"
#

/*!40000 ALTER TABLE `tp_article` DISABLE KEYS */;
INSERT INTO `tp_article` VALUES (28,'货到付款','','','','','',NULL,'',5,0,1,50,1519055314),(29,'配送与支付智能查询','','','','','',NULL,'',5,0,1,50,1519055342),(30,'支付方式说明','','','','','',NULL,'',5,0,1,50,1519055358),(31,'资金管理','','','','','',NULL,'',32,0,1,50,1519055371),(32,'我的收藏','','','','','',NULL,'',32,0,1,50,1519055389),(33,'我的订单','','','','','',NULL,'',32,0,1,50,1519055400),(37,'网站故障报告','','','','','',NULL,'',31,0,1,50,1519055461),(38,'选购咨询','','','','','',NULL,'',31,0,1,50,1519055475),(39,'投诉与建议','','','','','',NULL,'',31,0,1,50,1519055490),(46,'售后流程','','','','','',NULL,'',4,0,1,50,1519197541),(47,'购物流程','','','','','',NULL,'',4,0,1,50,1519197553),(48,'退换货原则','','','','','',NULL,'',10,0,1,50,1519197570),(49,'售后服务保证','','','','','',NULL,'',10,0,1,50,1519197584),(50,'隐私保护','','','','','',NULL,'',3,0,1,50,1519197620),(51,'免责条款','','','','','',NULL,'',3,0,1,50,1519197637),(52,'公司简介','','','','','',NULL,'',3,0,1,50,1519197648),(53,'意见反馈','','','','','',NULL,'',3,0,1,50,1519197659),(54,'联系我们','','','','','',NULL,'',3,0,1,50,1519197670);
/*!40000 ALTER TABLE `tp_article` ENABLE KEYS */;

#
# Structure for table "tp_attr"
#

DROP TABLE IF EXISTS `tp_attr`;
CREATE TABLE `tp_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(32) DEFAULT NULL COMMENT '属性名',
  `attr_type` tinyint(1) unsigned DEFAULT '1' COMMENT '属性类型1单选2唯一',
  `attr_values` varchar(255) DEFAULT NULL COMMENT '可选值',
  `type_id` smallint(5) unsigned DEFAULT NULL COMMENT 'tp_type的id ,所属类型',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "tp_attr"
#

/*!40000 ALTER TABLE `tp_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `tp_attr` ENABLE KEYS */;

#
# Structure for table "tp_brand"
#

DROP TABLE IF EXISTS `tp_brand`;
CREATE TABLE `tp_brand` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(64) DEFAULT NULL,
  `brand_url` varchar(64) DEFAULT NULL COMMENT '品牌官网地址',
  `brand_img` varchar(128) DEFAULT NULL,
  `brand_desc` varchar(255) DEFAULT NULL,
  `sort` smallint(5) unsigned DEFAULT '50' COMMENT '排序',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1显示0隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Data for table "tp_brand"
#

/*!40000 ALTER TABLE `tp_brand` DISABLE KEYS */;
INSERT INTO `tp_brand` VALUES (1,'海尔','',NULL,'',50,1),(2,'格力','',NULL,'',50,1),(3,'索尼','',NULL,'',50,1),(4,'迪奥','http://444','20180222/f0eb49f88f03d29a4f4187e8364b654b.jpg','',50,1),(5,'金士顿','','20180222/38a4f8712ffdc6a6ceb31ad06f62ace2.jpg','',50,1),(6,'华为','','20180222/1b8c62d585508a46c4c6185ce32beb8d.jpg','',50,1),(7,'apple','','20180222/38fa085757f1a56dbeaa624c6dcfa23c.jpg','',50,1);
/*!40000 ALTER TABLE `tp_brand` ENABLE KEYS */;

#
# Structure for table "tp_cate"
#

DROP TABLE IF EXISTS `tp_cate`;
CREATE TABLE `tp_cate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(64) DEFAULT NULL,
  `cate_type` tinyint(1) unsigned DEFAULT '5' COMMENT '栏目类型，1.系统分类，2.帮助分类，3.网店帮助，4.网店信息，5.普通分类',
  `thumb` varchar(128) DEFAULT NULL,
  `banner` varchar(128) DEFAULT NULL,
  `keywords` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `show_nav` tinyint(3) unsigned DEFAULT '0' COMMENT '不显示到导航',
  `allow_son` tinyint(1) unsigned DEFAULT '1' COMMENT '是否可以添加子栏目',
  `sort` smallint(5) unsigned DEFAULT '50' COMMENT '排序',
  `pid` smallint(5) unsigned DEFAULT '0' COMMENT '默认顶级栏目',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

#
# Data for table "tp_cate"
#

/*!40000 ALTER TABLE `tp_cate` DISABLE KEYS */;
INSERT INTO `tp_cate` VALUES (1,'系统',1,'20180213/134ca7ebb8de5d296d334ff0abec0bc9.jpg',NULL,NULL,NULL,0,0,1,0),(2,'网店帮助',2,'20180213/2082fa4cff560f8b11740f66490b3e3f.jpg','20180213/2ebe04c9110dc2d29027f34218a8ac98.jpg',NULL,NULL,0,1,2,1),(3,'网店信息',4,NULL,NULL,'','',0,0,50,1),(4,'新手上路',3,NULL,NULL,'','',1,0,5,2),(5,'配送与支付',3,'20180213/feb442570bd5974b852751d849462960.jpg',NULL,'111','22',1,0,4,2),(10,'服务保证',3,NULL,NULL,'','',1,0,2,2),(31,'联系我们',3,NULL,NULL,'','',1,0,1,2),(32,'会员中心',3,NULL,NULL,'','',1,0,3,2),(33,'公告',5,NULL,NULL,'','',1,1,50,0),(34,'商城公告',5,NULL,NULL,'','',1,1,50,33);
/*!40000 ALTER TABLE `tp_cate` ENABLE KEYS */;

#
# Structure for table "tp_category"
#

DROP TABLE IF EXISTS `tp_category`;
CREATE TABLE `tp_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(32) DEFAULT NULL,
  `thumb` varchar(128) DEFAULT NULL COMMENT '缩略图',
  `banner` varchar(128) DEFAULT NULL COMMENT '分类栏目banner图',
  `keywords` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort` int(11) unsigned DEFAULT '50',
  `show_cate` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示',
  `pid` smallint(5) unsigned DEFAULT '0' COMMENT '上级栏目',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

#
# Data for table "tp_category"
#

/*!40000 ALTER TABLE `tp_category` DISABLE KEYS */;
INSERT INTO `tp_category` VALUES (1,'家用电器',NULL,NULL,'','',50,1,0),(2,'手机数码',NULL,NULL,'','',50,1,0),(5,'服装',NULL,NULL,'','',50,1,0),(6,'大家电',NULL,NULL,'','',50,1,1),(7,'生活电器',NULL,NULL,'','',50,1,1),(8,'智能设备',NULL,NULL,'','',50,1,2),(9,'数码配件',NULL,NULL,'','',50,1,2),(11,'男装',NULL,NULL,'','',50,1,5),(12,'女装',NULL,NULL,'','',50,1,5),(13,'平板电视',NULL,NULL,'','',50,1,6),(14,'空调',NULL,NULL,'','',50,1,6),(15,'冰箱',NULL,NULL,'','',50,1,6),(16,'电风扇',NULL,NULL,'','',50,1,7),(17,'净化器',NULL,NULL,'','',50,1,7),(18,'加湿器',NULL,NULL,'','',50,1,7),(19,'扫地机器人',NULL,NULL,'','',50,1,7),(20,'厨房电器',NULL,NULL,'','',50,1,1),(21,'电饭煲',NULL,NULL,'','',50,1,20),(22,'电压力锅',NULL,NULL,'','',50,1,20);
/*!40000 ALTER TABLE `tp_category` ENABLE KEYS */;

#
# Structure for table "tp_category_brands"
#

DROP TABLE IF EXISTS `tp_category_brands`;
CREATE TABLE `tp_category_brands` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `brand_ids` varchar(64) DEFAULT NULL COMMENT '关联品牌的Id列表',
  `pro_img` varchar(128) DEFAULT NULL COMMENT '推广图片',
  `pro_url` varchar(64) DEFAULT NULL COMMENT '推广地址',
  `category_id` smallint(6) unsigned DEFAULT NULL COMMENT '关联栏目Id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "tp_category_brands"
#

/*!40000 ALTER TABLE `tp_category_brands` DISABLE KEYS */;
INSERT INTO `tp_category_brands` VALUES (6,'4,5','20180222/5e39f2eec311f997ca2f66b5a7cd543f.jpg','http://565',1),(7,'5,6,7','20180222/cccdf4e71f14b1081b51578f3fda7da7.jpg','',2);
/*!40000 ALTER TABLE `tp_category_brands` ENABLE KEYS */;

#
# Structure for table "tp_category_words"
#

DROP TABLE IF EXISTS `tp_category_words`;
CREATE TABLE `tp_category_words` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned DEFAULT NULL COMMENT '关联的顶级栏目',
  `word` varchar(64) DEFAULT NULL COMMENT '链接字',
  `link_url` varchar(64) DEFAULT NULL COMMENT '链接',
  `sort` varchar(255) DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "tp_category_words"
#

/*!40000 ALTER TABLE `tp_category_words` DISABLE KEYS */;
INSERT INTO `tp_category_words` VALUES (1,1,'家电城','http://#1111','3'),(2,1,'智能生活馆','','1'),(3,1,'京东净化器','','2'),(4,2,'网上营业厅','','3'),(5,2,'配件城','http://11111','50');
/*!40000 ALTER TABLE `tp_category_words` ENABLE KEYS */;

#
# Structure for table "tp_conf"
#

DROP TABLE IF EXISTS `tp_conf`;
CREATE TABLE `tp_conf` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enname` varchar(64) DEFAULT NULL COMMENT '英文名',
  `cnname` varchar(32) DEFAULT NULL COMMENT '中文名',
  `form_type` varchar(10) DEFAULT 'input' COMMENT '表单类型',
  `conf_type` tinyint(1) unsigned DEFAULT '1' COMMENT '配置类型1网店2商品',
  `values` varchar(64) DEFAULT NULL COMMENT '可选项',
  `value` varchar(255) DEFAULT NULL COMMENT '默认值',
  `sort` smallint(5) unsigned DEFAULT '50',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "tp_conf"
#

/*!40000 ALTER TABLE `tp_conf` DISABLE KEYS */;
INSERT INTO `tp_conf` VALUES (1,'sitename','网站名称','input',1,'','传酷商城Shop',50),(2,'beian','备案号','input',1,'','沪ICP备16013433号-4',50),(3,'keywords','站点关键字','input',1,'','传酷商城系统',50),(4,'description','站点描述','textarea',1,'','传酷商城Shop，专业B2C商城系统。',50),(5,'cmsname','程序名称','input',1,'','传酷Shop1.0',50),(6,'search_keywords','搜索关键词','textarea',1,'','周大福,迪奥,iphoneX,mac',50),(7,'search_default','搜索框默认值','input',1,'','手机',50),(8,'siteurl','网站域名','input',1,'','www.chuankukeji.com',50),(9,'tel','座机电话','input',1,'','021-80392515',50),(10,'qq','QQ','input',1,'','69719701',50);
/*!40000 ALTER TABLE `tp_conf` ENABLE KEYS */;

#
# Structure for table "tp_goods"
#

DROP TABLE IF EXISTS `tp_goods`;
CREATE TABLE `tp_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(128) DEFAULT NULL COMMENT '商品名称',
  `goods_code` char(16) DEFAULT NULL COMMENT '商品编号',
  `og_thumb` varchar(128) DEFAULT NULL COMMENT '原图',
  `sm_thumb` varchar(128) DEFAULT NULL COMMENT '小图',
  `md_thumb` varchar(128) DEFAULT NULL COMMENT '中图',
  `big_thumb` varchar(128) DEFAULT NULL COMMENT '大图',
  `market_price` decimal(10,2) unsigned DEFAULT NULL COMMENT '市场价',
  `shop_price` decimal(10,2) unsigned DEFAULT NULL COMMENT '本店价',
  `on_sale` tinyint(1) unsigned DEFAULT '1' COMMENT '上架',
  `category_id` smallint(5) unsigned DEFAULT NULL COMMENT '所属栏目',
  `brand_id` smallint(5) unsigned DEFAULT '0' COMMENT '品牌ID',
  `type_id` smallint(5) unsigned DEFAULT '0' COMMENT '所属类型,默认没有',
  `content` longtext COMMENT '商品描述',
  `weight` decimal(10,2) unsigned DEFAULT NULL COMMENT '重量',
  `weight_unit` varchar(10) DEFAULT 'kg' COMMENT '单位默认值',
  PRIMARY KEY (`id`),
  KEY `其他索引` (`type_id`,`brand_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "tp_goods"
#

/*!40000 ALTER TABLE `tp_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `tp_goods` ENABLE KEYS */;

#
# Structure for table "tp_goods_attr"
#

DROP TABLE IF EXISTS `tp_goods_attr`;
CREATE TABLE `tp_goods_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attr_id` smallint(5) unsigned DEFAULT NULL COMMENT '属性ID，如：颜色',
  `attr_value` varchar(64) DEFAULT NULL COMMENT '属性值，如：红色',
  `attr_price` decimal(10,2) unsigned DEFAULT NULL COMMENT '颜色中的红色价格',
  `goods_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性表';

#
# Data for table "tp_goods_attr"
#

/*!40000 ALTER TABLE `tp_goods_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `tp_goods_attr` ENABLE KEYS */;

#
# Structure for table "tp_goods_photo"
#

DROP TABLE IF EXISTS `tp_goods_photo`;
CREATE TABLE `tp_goods_photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned DEFAULT NULL COMMENT '商品ID',
  `og_photo` varchar(128) DEFAULT NULL,
  `sm_photo` varchar(128) DEFAULT NULL,
  `md_photo` varchar(128) DEFAULT NULL,
  `big_photo` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

#
# Data for table "tp_goods_photo"
#

/*!40000 ALTER TABLE `tp_goods_photo` DISABLE KEYS */;
INSERT INTO `tp_goods_photo` VALUES (5,6,'20180218\\063f12bafcce8ef3a09489091a7e3578.jpg','20180218\\sm_063f12bafcce8ef3a09489091a7e3578.jpg','20180218\\md_063f12bafcce8ef3a09489091a7e3578.jpg','20180218\\big_063f12bafcce8ef3a09489091a7e3578.jpg'),(6,6,'20180218\\e6b95ea63043a9138f4d533e2cd021b3.jpg','20180218\\sm_e6b95ea63043a9138f4d533e2cd021b3.jpg','20180218\\md_e6b95ea63043a9138f4d533e2cd021b3.jpg','20180218\\big_e6b95ea63043a9138f4d533e2cd021b3.jpg'),(8,6,'20180219\\b9a179cf8d8237833522c38bd43311fe.jpg','20180219\\sm_b9a179cf8d8237833522c38bd43311fe.jpg','20180219\\md_b9a179cf8d8237833522c38bd43311fe.jpg','20180219\\big_b9a179cf8d8237833522c38bd43311fe.jpg'),(9,2,'20180219\\4a42fbf1135b38984ad03032dd7c9c52.jpg','20180219\\sm_4a42fbf1135b38984ad03032dd7c9c52.jpg','20180219\\md_4a42fbf1135b38984ad03032dd7c9c52.jpg','20180219\\big_4a42fbf1135b38984ad03032dd7c9c52.jpg'),(10,2,'20180219\\e60b18851e06186b89d70cb83b002fcd.jpg','20180219\\sm_e60b18851e06186b89d70cb83b002fcd.jpg','20180219\\md_e60b18851e06186b89d70cb83b002fcd.jpg','20180219\\big_e60b18851e06186b89d70cb83b002fcd.jpg'),(11,1,'20180219\\9ea71e45bf5c809c8daf7fe0961f33d7.jpg','20180219\\sm_9ea71e45bf5c809c8daf7fe0961f33d7.jpg','20180219\\md_9ea71e45bf5c809c8daf7fe0961f33d7.jpg','20180219\\big_9ea71e45bf5c809c8daf7fe0961f33d7.jpg'),(12,1,'20180219\\d0c655d4133e68659ac76c36796f6b44.jpg','20180219\\sm_d0c655d4133e68659ac76c36796f6b44.jpg','20180219\\md_d0c655d4133e68659ac76c36796f6b44.jpg','20180219\\big_d0c655d4133e68659ac76c36796f6b44.jpg'),(13,1,'20180219\\c883dea2169fdc726867113199b5afba.jpg','20180219\\sm_c883dea2169fdc726867113199b5afba.jpg','20180219\\md_c883dea2169fdc726867113199b5afba.jpg','20180219\\big_c883dea2169fdc726867113199b5afba.jpg');
/*!40000 ALTER TABLE `tp_goods_photo` ENABLE KEYS */;

#
# Structure for table "tp_link"
#

DROP TABLE IF EXISTS `tp_link`;
CREATE TABLE `tp_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  `logo` varchar(128) DEFAULT NULL,
  `url` varchar(64) DEFAULT NULL,
  `description` varchar(64) DEFAULT NULL,
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '1文字2图片',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1显示2隐藏',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "tp_link"
#

/*!40000 ALTER TABLE `tp_link` DISABLE KEYS */;
INSERT INTO `tp_link` VALUES (3,'上海传酷科技','20180215/a4605c11dbd1eed86ac618d529c5ffa9.png','http://chuankukeji.com','传酷科技是一家高端网站建设公司',1,1),(4,'田伟博客',NULL,'http://tianweiseo.com','专业SEO服务',1,1);
/*!40000 ALTER TABLE `tp_link` ENABLE KEYS */;

#
# Structure for table "tp_member_level"
#

DROP TABLE IF EXISTS `tp_member_level`;
CREATE TABLE `tp_member_level` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(32) DEFAULT NULL COMMENT '等级名称',
  `bot_point` int(11) unsigned DEFAULT NULL COMMENT '积分下限',
  `top_point` int(11) unsigned DEFAULT NULL COMMENT '积分上限',
  `rate` tinyint(3) unsigned DEFAULT '100' COMMENT '折扣率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Data for table "tp_member_level"
#

/*!40000 ALTER TABLE `tp_member_level` DISABLE KEYS */;
INSERT INTO `tp_member_level` VALUES (5,'注册会员',0,10001,100),(7,'中级会员',10000,20000,90),(8,'高级会员',50000,100000,50);
/*!40000 ALTER TABLE `tp_member_level` ENABLE KEYS */;

#
# Structure for table "tp_member_price"
#

DROP TABLE IF EXISTS `tp_member_price`;
CREATE TABLE `tp_member_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '一件商品4条价格记录',
  `mprice` decimal(10,2) unsigned DEFAULT NULL,
  `mlevel_id` smallint(6) unsigned DEFAULT NULL COMMENT '会员等级',
  `goods_id` int(11) unsigned DEFAULT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

#
# Data for table "tp_member_price"
#

/*!40000 ALTER TABLE `tp_member_price` DISABLE KEYS */;
INSERT INTO `tp_member_price` VALUES (21,1000.00,5,5),(22,2000.00,8,5),(23,3000.00,7,5);
/*!40000 ALTER TABLE `tp_member_price` ENABLE KEYS */;

#
# Structure for table "tp_nav"
#

DROP TABLE IF EXISTS `tp_nav`;
CREATE TABLE `tp_nav` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(32) DEFAULT NULL COMMENT '栏目名称',
  `nav_url` varchar(128) DEFAULT NULL COMMENT '栏目地址',
  `open` tinyint(1) unsigned DEFAULT '1' COMMENT '打开方式1=blank;2=self',
  `sort` smallint(6) DEFAULT '1' COMMENT '排序',
  `pos` varchar(8) DEFAULT 'top' COMMENT '顶部，中间mid，底部bot',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "tp_nav"
#

/*!40000 ALTER TABLE `tp_nav` DISABLE KEYS */;
INSERT INTO `tp_nav` VALUES (2,'食品特产','http://baidu.com',1,6,'mid'),(3,'服装城','http://#',1,3,'mid'),(4,'大家电','http://#',1,7,'mid'),(5,'箱包','http://11111',1,8,'mid'),(6,'品牌专区','http://#',1,1,'mid'),(7,'我的订单','http://#',1,1,'top'),(8,'我的浏览','http://#',1,1,'top'),(9,'我的收藏','http://#',1,1,'top'),(10,'客户服务','http://baidu.com',1,1,'top');
/*!40000 ALTER TABLE `tp_nav` ENABLE KEYS */;

#
# Structure for table "tp_rec_item"
#

DROP TABLE IF EXISTS `tp_rec_item`;
CREATE TABLE `tp_rec_item` (
  `rec_id` smallint(5) unsigned DEFAULT NULL COMMENT '推荐位id',
  `value_id` mediumint(8) unsigned DEFAULT NULL COMMENT '商品或分类id',
  `value_type` tinyint(1) unsigned DEFAULT '1' COMMENT '推荐类型：商品或分类'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "tp_rec_item"
#

/*!40000 ALTER TABLE `tp_rec_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `tp_rec_item` ENABLE KEYS */;

#
# Structure for table "tp_recommend"
#

DROP TABLE IF EXISTS `tp_recommend`;
CREATE TABLE `tp_recommend` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rec_name` varchar(64) DEFAULT NULL COMMENT '推荐位名称',
  `rec_type` tinyint(1) unsigned DEFAULT NULL COMMENT '推荐位类型1商品2分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "tp_recommend"
#

/*!40000 ALTER TABLE `tp_recommend` DISABLE KEYS */;
INSERT INTO `tp_recommend` VALUES (2,'热卖商品',1),(3,'最新商品',1),(4,'限时抢购',1),(5,'首页推荐',2),(6,'推荐分类',2);
/*!40000 ALTER TABLE `tp_recommend` ENABLE KEYS */;

#
# Structure for table "tp_stock"
#

DROP TABLE IF EXISTS `tp_stock`;
CREATE TABLE `tp_stock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned DEFAULT NULL,
  `goods_number` mediumint(8) unsigned DEFAULT NULL COMMENT '数量',
  `goods_attr` varchar(32) DEFAULT NULL COMMENT '属性代表字符',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Data for table "tp_stock"
#

/*!40000 ALTER TABLE `tp_stock` DISABLE KEYS */;
INSERT INTO `tp_stock` VALUES (7,1,100,'9,12,14,17'),(8,1,500,'9,12,15,17'),(9,3,800,'23');
/*!40000 ALTER TABLE `tp_stock` ENABLE KEYS */;

#
# Structure for table "tp_type"
#

DROP TABLE IF EXISTS `tp_type`;
CREATE TABLE `tp_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(32) DEFAULT NULL COMMENT '商品类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Data for table "tp_type"
#

/*!40000 ALTER TABLE `tp_type` DISABLE KEYS */;
INSERT INTO `tp_type` VALUES (1,'电脑'),(3,'服装'),(4,'手机');
/*!40000 ALTER TABLE `tp_type` ENABLE KEYS */;
