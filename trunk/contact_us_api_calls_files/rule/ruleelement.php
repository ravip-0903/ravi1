<?php
//include_once( "include.php" );

class RuleElement  {
	var $name;
	
	function RuleElement( $name ) {
		$this->name = $name;
	}
	
	function __construct( $name ) {
		$this->name = $name;		
	}
	
	function getType() {
		return "RuleElement";
	}
}

class Operator extends RuleElement {
	var $operators;
	
	function Operator( $operator ) {
		$this->operators = array( "AND", "OR", "NOT", "EQUALTO", "NOTEQUALTO", "LESSTHAN", "GREATERTHAN", "LESSTHANOREQUALTO", "GREATERTHANOREQUALTO" );
		if( in_array( $operator, $this->operators ) ) {
			parent::Operator( $operator );
		}
		else {
			throw new Exception( $operator . " is not a valid operator." );
		}
	}
	
	function __construct( $operator ) {
		$this->operators = array( "AND", "OR", "NOT", "EQUALTO", "NOTEQUALTO", "LESSTHAN", "GREATERTHAN", "LESSTHANOREQUALTO", "GREATERTHANOREQUALTO" );
		if( in_array( $operator, $this->operators ) ) {
			parent::__construct( $operator );
		}
		else {
			throw new Exception( $operator . " is not a valid operator." );
		}
	}
	
	function getType() {
		return "Operator";
	}
	
	function toString() {
		return $this->name;
	}
}

class Proposition extends RuleElement {
	var $value;
	
	function Proposition( $name, $truthValue ) {
		$this->value = $truthValue;
		parent::RuleElement( $name );
	}
	
	function __construct( $name, $truthValue ) {
		$this->value = $truthValue;
		parent::RuleElement( $name );
	}
	
	function getType() {
		return "Proposition";
	}
	
	function toString() {
		$truthValue = "FALSE";
		if( $this->value == true ) {
			$truthValue = "TRUE";
		}
		return "Proposition statement = " . $this->name . ", value = " . $truthValue;
	}
	
	public function logicalAnd( $proposition ) {
		$resultName  = "( " . $this->name . " AND " . $proposition->name . " )";
		$resultValue = ( $this->value and $proposition->value );
		return new Proposition( $resultName, $resultValue );
	}
	
	function logicalOr( $proposition ) {
		$resultName  = "( " . $this->name . " OR " . $proposition->name . " )";
		$resultValue = ( $this->value or $proposition->value );
		return new Proposition( $resultName, $resultValue );
	}
	
	function logicalNot( $proposition ) {
		$resultName  = "( NOT " . $proposition->name . " )";
		$resultValue = ( !$proposition->value );
		return new Proposition( $resultName, $resultValue );
	}
	
	function logicalXor( $proposition ) {
		$resultName  = "( " . $this->name . " XOR " . $proposition->name . " )";
		$resultValue = ( $this->value xor $proposition->value );
		return new Proposition( $resultName, $resultValue );
	}
}

class Variable extends RuleElement {
	var $value;
	
	function Variable( $name, $value ) {
		$this->value = $value;
		parent::RuleElement( $name );
	}
	
	function __construct( $name, $value ) {
		$this->value = $value;
		parent::__construct( $name );
	}
	
	function getType() {
		return "Variable";
	}
	
	function toString() {
		return "Variable name = " . $this->name . ", value = " . $this->value;
	}
	
	function equalTo( $variable ) {
		$statement = "( " . $this->name . " == " . $variable->name . " )";
		$truthValue = ( $this->value == $variable->value );
		return new Proposition( $statement, $truthValue );
	}
	
	function notEqualTo( $variable ) {
		$statement = "( " . $this->name . " != " . $variable->name . " )";
		$truthValue = ( $this->value != $variable->value );
		return new Proposition( $statement, $truthValue );
	}
	
	function lessThan( $variable ) {
		$statement = "( " . $this->name . " < " . $variable->name . " )";
		$truthValue = ( $this->value < $variable->value );
		return new Proposition( $statement, $truthValue );
	}
	
	function lessThanOrEqualTo( $variable ) {
		$statement = "( " . $this->name . " <= " . $variable->name . " )";
		$truthValue = ( $this->value <= $variable->value );
		return new Proposition( $statement, $truthValue );
	}
	
	function greaterThan( $variable ) {
		$statement = "( " . $this->name . " > " . $variable->name . " )";
		$truthValue = ( $this->value > $variable->value );
		return new Proposition( $statement, $truthValue );
	}
	
	function greaterThanOrEqualTo( $variable ) {
		$statement = "( " . $this->name . " >= " . $variable->name . " )";
		$truthValue = ( $this->value >= $variable->value );
		return new Proposition( $statement, $truthValue );
	}
}
?>