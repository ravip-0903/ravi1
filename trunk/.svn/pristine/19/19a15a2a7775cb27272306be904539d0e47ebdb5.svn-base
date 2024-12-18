<?php 
class RuleLoader  {

	private $strategy = null;
	
	function RuleLoader() {
		parent::Model();
		$this->load->model( 'rule/Rule', 'rule' );
		$this->load->model( 'rule/RuleContext', 'ruleContext' );
		$this->load->database();
	}
	
	public function setStrategy( $strategy ) {
		$this->strategy = $strategy;
		$strategy->rule = $this->rule;
		$strategy->ruleContext = $this->ruleContext;
	}
	
	public function loadRule( $fileName ) {
		return $this->strategy->loadRule( $fileName );		
	}
	
	public function loadRuleContext( $fileName, $id ) {
		return $this->strategy->loadRuleContext( $fileName, $id );
	}
}
?>