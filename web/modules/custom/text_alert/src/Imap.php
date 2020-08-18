<?php

namespace Drupal\text_alert;

class Imap {
 
	// Imap server connection
	public $conn;

	// Inbox storage and inbox message count
	private $inbox;
	private $msg_cnt;

	// Email login credentials
	private $server = 'email.360-biz.com';
	private $user   = 'alert-text@360-biz.com';
	private $pass   = '8qSsLT4fLuGzsbjY664L';
	private $port   = 143; // adjust according to server settings

	// Connect to the server and get the inbox emails
	function __construct() {
		$this->connect();
		$this->inbox();
	}

	// Close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}

	// Open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	function connect() {
		$this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
	}
	
	// Get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index = NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif (!is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}

		return $this->inbox[0];
	}

	// read the inbox
	function inbox() {
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for ($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i),
				'body'      => imap_fetchbody($this->conn, $i, 1),
				'structure' => imap_fetchstructure($this->conn, $i)
			);
		}

		$this->inbox = $in;
	}
	
	function get_inbox() {
		return $this->inbox;
  }
    
  // Functions Marc Wrote  
  public function delete_email($message_number) {
		imap_delete($this->conn, $message_number);
		imap_expunge($this->conn);
  }
}
