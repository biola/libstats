-- MySQL dump 9.08
--
-- Host: localhost    Database: libstats
---------------------------------------------------------
-- Server version	4.0.13

--
-- Table structure for table 'admin'
--

CREATE TABLE admin (
  parent_table varchar(100) NOT NULL default '',
  parent_pk varchar(100) NOT NULL default '',
  descriptor varchar(100) NOT NULL default '',
  display_name varchar(100) NOT NULL default '',
  parent_finder varchar(100) NOT NULL default '',
  edit_action_class varchar(100) NOT NULL default '',
  bridge_table varchar(100) NOT NULL default '',
  bridge_table_view int(10) NOT NULL default '0',
  PRIMARY KEY  (parent_table)
) TYPE=MyISAM;

--
-- Dumping data for table 'admin'
--

INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('locations','location_id','location_name','Locations','LocationFinder','libraryLocation.do','library_locations',1);
INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('patron_types','patron_type_id','patron_type','Patron Types','PatronTypeFinder','libraryPatronType.do','library_patron_types',1);
INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('question_formats','question_format_id','question_format','Question Formats','QuestionFormatFinder','libraryQuestionFormat.do','library_quesiton_formats',1);
INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('question_types','question_type_id','question_type','Question Types','QuestionTypeFinder','libraryQuestionType.do','library_question_types',1);
INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('time_spent_options','time_spent_id','time_spent','Time Spent Options','TimeSpentFinder','libraryTimeSpent.do','library_time_spent_options',1);
INSERT INTO admin (parent_table, parent_pk, descriptor, display_name, parent_finder, edit_action_class, bridge_table, bridge_table_view) VALUES ('libraries','library_id','short_name','Library','LibraryFinder','','',0);


--
-- Table structure for table 'cookie_logins'
--

CREATE TABLE cookie_logins (
  cookie_login_id int(11) unsigned NOT NULL auto_increment,
  cookie varchar(50) NOT NULL default '',
  user_id int(11) unsigned NOT NULL default '0',
  date_last_used datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (cookie_login_id),
  UNIQUE KEY cookie (cookie)
) TYPE=MyISAM;

--
-- Dumping data for table 'cookie_logins'
--


--
-- Table structure for table 'help_list'
--

CREATE TABLE help_list (
  help_id int(11) NOT NULL auto_increment,
  description text NOT NULL,
  related_table varchar(100) NOT NULL default '',
  help_name varchar(100) NOT NULL default '',
  PRIMARY KEY  (help_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'help_list'
--


--
-- Table structure for table 'libraries'
--

CREATE TABLE libraries (
  library_id int(11) NOT NULL auto_increment,
  full_name varchar(255) NOT NULL default '',
  short_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (library_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'libraries'
--

INSERT INTO libraries (library_id, full_name, short_name) VALUES (1,'Library','Library');

--
-- Table structure for table 'library_locations'
--

CREATE TABLE library_locations (
  location_id int(11) unsigned NOT NULL auto_increment,
  library_id int(11) unsigned NOT NULL default '0',
  location_name varchar(255) NOT NULL default '',
  list_order int(11) NOT NULL default '0',
  PRIMARY KEY  (location_id,library_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'library_locations'
--

INSERT INTO library_locations (location_id, library_id, location_name, list_order) VALUES (2,1,'Circulation',1);
INSERT INTO library_locations (location_id, library_id, location_name, list_order) VALUES (3,1,'Office',2);
INSERT INTO library_locations (location_id, library_id, location_name, list_order) VALUES (1,1,'Reference',0);

--
-- Table structure for table 'library_patron_types'
--

CREATE TABLE library_patron_types (
  patron_type_id int(11) unsigned NOT NULL default '0',
  library_id int(11) unsigned NOT NULL default '0',
  list_order int(11) NOT NULL default '0',
  PRIMARY KEY  (library_id,patron_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'library_patron_types'
--

INSERT INTO library_patron_types (patron_type_id, library_id, list_order) VALUES (1,1,0);
INSERT INTO library_patron_types (patron_type_id, library_id, list_order) VALUES (2,1,0);
INSERT INTO library_patron_types (patron_type_id, library_id, list_order) VALUES (3,1,0);

--
-- Table structure for table 'library_question_formats'
--

CREATE TABLE library_question_formats (
  question_format_id int(11) unsigned NOT NULL default '0',
  library_id int(11) unsigned NOT NULL default '0',
  list_order int(11) NOT NULL default '0',
  PRIMARY KEY  (question_format_id,library_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'library_question_formats'
--

INSERT INTO library_question_formats (question_format_id, library_id, list_order) VALUES (1,1,0);
INSERT INTO library_question_formats (question_format_id, library_id, list_order) VALUES (2,1,0);
INSERT INTO library_question_formats (question_format_id, library_id, list_order) VALUES (3,1,0);

--
-- Table structure for table 'library_question_types'
--

CREATE TABLE library_question_types (
  question_type_id int(11) unsigned NOT NULL default '0',
  library_id int(11) unsigned NOT NULL default '0',
  list_order int(11) NOT NULL default '0',
  PRIMARY KEY  (question_type_id,library_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'library_question_types'
--

INSERT INTO library_question_types (question_type_id, library_id, list_order) VALUES (1,1,0);
INSERT INTO library_question_types (question_type_id, library_id, list_order) VALUES (2,1,0);

--
-- Table structure for table 'library_time_spent_options'
--

CREATE TABLE library_time_spent_options (
  time_spent_id int(11) unsigned NOT NULL default '0',
  library_id int(11) unsigned NOT NULL default '0',
  list_order int(11) NOT NULL default '0',
  PRIMARY KEY  (time_spent_id,library_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'library_time_spent_options'
--

INSERT INTO library_time_spent_options (time_spent_id, library_id, list_order) VALUES (1,1,0);
INSERT INTO library_time_spent_options (time_spent_id, library_id, list_order) VALUES (2,1,0);

--
-- Table structure for table 'locations'
--

CREATE TABLE locations (
  location_id int(11) NOT NULL auto_increment,
  location_name varchar(100) NOT NULL default '',
  parent_list int(11) NOT NULL default '0',
  description text NOT NULL,
  examples text NOT NULL,
  PRIMARY KEY  (location_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'locations'
--

INSERT INTO locations (location_id, location_name, parent_list, description, examples) VALUES (1,'Reference',0,'Reference Desk','');
INSERT INTO locations (location_id, location_name, parent_list, description, examples) VALUES (2,'Circulation',0,'Circ Desk','');
INSERT INTO locations (location_id, location_name, parent_list, description, examples) VALUES (3,'Office',0,'A staff office','');

--
-- Table structure for table 'patron_types'
--

CREATE TABLE patron_types (
  examples text NOT NULL,
  description text NOT NULL,
  patron_type_id int(11) NOT NULL auto_increment,
  patron_type varchar(50) NOT NULL default '',
  parent_list int(11) NOT NULL default '0',
  PRIMARY KEY  (patron_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'patron_types'
--

INSERT INTO patron_types (examples, description, patron_type_id, patron_type, parent_list) VALUES ('','Any type of student',1,'Student',0);
INSERT INTO patron_types (examples, description, patron_type_id, patron_type, parent_list) VALUES ('','A faculty or staff member',2,'Fac/Staff',0);
INSERT INTO patron_types (examples, description, patron_type_id, patron_type, parent_list) VALUES ('','A patron from the surrounding community',3,'Community',0);

--
-- Table structure for table 'question_formats'
--

CREATE TABLE question_formats (
  examples text NOT NULL,
  description text NOT NULL,
  question_format_id int(11) unsigned NOT NULL auto_increment,
  question_format varchar(50) NOT NULL default '',
  parent_list int(11) NOT NULL default '0',
  PRIMARY KEY  (question_format_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'question_formats'
--

INSERT INTO question_formats (examples, description, question_format_id, question_format, parent_list) VALUES ('','A patron asks for help in person',1,'Walk-Up',0);
INSERT INTO question_formats (examples, description, question_format_id, question_format, parent_list) VALUES ('','',3,'Phone',0);
INSERT INTO question_formats (examples, description, question_format_id, question_format, parent_list) VALUES ('','A question asked via email',2,'Email',0);

--
-- Table structure for table 'question_types'
--

CREATE TABLE question_types (
  examples text NOT NULL,
  description text NOT NULL,
  question_type_id int(11) unsigned NOT NULL auto_increment,
  parent_list int(11) NOT NULL default '0',
  question_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (question_type_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'question_types'
--

INSERT INTO question_types (examples, description, question_type_id, parent_list, question_type) VALUES ('','A purely directional question',1,0,'Directional');
INSERT INTO question_types (examples, description, question_type_id, parent_list, question_type) VALUES ('','',2,0,'Reference');

--
-- Table structure for table 'questions'
--

CREATE TABLE questions (
  backup int(11) NOT NULL default '0',
  updated int(11) NOT NULL default '0',
  question_id int(11) unsigned NOT NULL auto_increment,
  library_id int(11) unsigned NOT NULL default '0',
  location_id int(11) unsigned NOT NULL default '0',
  question_type_id int(11) unsigned NOT NULL default '0',
  question_type_other varchar(30) default NULL,
  time_spent_id int(11) unsigned default '0',
  referral_id int(11) unsigned default '0',
  patron_type_id int(11) unsigned default '0',
  question_format_id int(11) unsigned default '0',
  initials varchar(10) NOT NULL default '',
  hide tinyint(2) unsigned NOT NULL default '0',
  obsolete tinyint(2) unsigned NOT NULL default '0',
  question_date datetime NOT NULL default '0000-00-00 00:00:00',
  client_ip varchar(20) NOT NULL default '',
  user_id int(11) unsigned NOT NULL default '0',
  answer text NOT NULL,
  question text NOT NULL,
  delete_hide int(10) unsigned NOT NULL default '0',
  date_added datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (question_id),
  KEY k_question_date (question_date),
  KEY initials (initials),
  KEY library_id (library_id),
  KEY question_id_libraries (library_id,client_ip(15),question_id),
  KEY library_question_index (delete_hide,library_id,question_id,hide),
  FULLTEXT KEY question (question),
  FULLTEXT KEY answer (answer)
) TYPE=MyISAM;

--
-- Dumping data for table 'questions'
--

--
-- Table structure for table 'time_spent_options'
--

CREATE TABLE time_spent_options (
  examples text NOT NULL,
  description text NOT NULL,
  parent_list int(11) NOT NULL default '0',
  time_spent_id int(10) unsigned NOT NULL auto_increment,
  time_spent varchar(100) NOT NULL default '',
  PRIMARY KEY  (time_spent_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'time_spent_options'
--

INSERT INTO time_spent_options (examples, description, parent_list, time_spent_id, time_spent) VALUES ('','',0,1,'0-9 minutes');
INSERT INTO time_spent_options (examples, description, parent_list, time_spent_id, time_spent) VALUES ('','',0,2,'10+ minutes');

--
-- Table structure for table 'users'
--

CREATE TABLE users (
  user_id int(11) NOT NULL auto_increment,
  username varchar(50) NOT NULL default '',
  password varchar(32) binary NOT NULL default '',
  library_id int(11) NOT NULL default '0',
  active tinyint(2) NOT NULL default '1',
  admin tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (user_id),
  UNIQUE KEY username (username)
) TYPE=MyISAM;

--
-- Dumping data for table 'users'
--

INSERT INTO users (user_id, username, password, library_id, active, admin) VALUES (1,'admin', md5('changeme'),1,1,1);

