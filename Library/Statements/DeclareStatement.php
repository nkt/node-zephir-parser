<?php

/*
 +----------------------------------------------------------------------+
 | Zephir Language                                                      |
 +----------------------------------------------------------------------+
 | Copyright (c) 2013 Zephir Team                                       |
 +----------------------------------------------------------------------+
 | This source file is subject to version 1.0 of the Zephir license,    |
 | that is bundled with this package in the file LICENSE, and is        |
 | available through the world-wide-web at the following url:           |
 | http://www.zephir-lang.com/license                                   |
 |                                                                      |
 | If you did not receive a copy of the Zephir license and are unable   |
 | to obtain it through the world-wide-web, please send a note to       |
 | license@zephir-lang.com so we can mail you a copy immediately.       |
 +----------------------------------------------------------------------+
*/

/**
 * DeclareStatement
 *
 * This creates variables in the current symbol table
 */
class DeclareStatement
{

	protected $_statement;

	public function __construct($statement)
	{
		$this->_statement = $statement;
	}

	/**
	 * Compiles the statement
	 *
	 * @param \CompilationContext $compilationContext
	 */
	public function compile(CompilationContext $compilationContext)
	{
		$statement = $this->_statement;

		if (!isset($statement['data-type'])) {
			throw new CompilerException("Data type is required", $this->_statement);
		}

		$variables = array();
		foreach ($statement['variables'] as $variable) {

			if ($compilationContext->symbolTable->hasVariable($variable['variable'])) {
				throw new CompilerException("Variable '" . $variable['variable'] . "' is already defined", $variable);
			}

			/**
			 * Variables are added to the symbol table
			 */
			if (isset($variable['expr'])) {
				$symbolVariable = $compilationContext->symbolTable->addVariable($statement['data-type'], $variable['variable'], $compilationContext, $variable['expr']);
			} else {
				$symbolVariable = $compilationContext->symbolTable->addVariable($statement['data-type'], $variable['variable'], $compilationContext);
			}

			/**
			 * Variables with a default value are initialized by default
			 */
			if (isset($variable['expr'])) {
				$symbolVariable->setIsInitialized(true);
			}

			if (isset($variable['expr'])) {
				$defaultValue = $variable['expr']['value'];
			} else {
				$defaultValue = null;
			}

			if ($defaultValue !== null) {

				switch ($statement['data-type']) {
					case 'int':
						switch ($variable['expr']['type']) {
							case 'int':
								break;
							default:
								throw new CompilerException('Invalid default type: ' . $variable['expr']['type'] . ' for data type: ' . $statement['data-type'], $variable);
						}
						break;
					default:
						throw new CompilerException('Invalid data type: ' . $statement['data-type'], $variable);
				}

				$symbolVariable->setDefaultInitValue($defaultValue);
				$symbolVariable->setIsInitialized(true);
			}
		}

	}
}