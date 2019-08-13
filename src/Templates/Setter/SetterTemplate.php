<?php declare(strict_types=1);

namespace Codegen\Templates\Setter;

use Codegen\Templates\AbstractTemplate;
use Nette\PhpGenerator\ClassType;

/**
 * @author Matej Bukovsky <matejbukovsky@gmail.com>
 */
class SetterTemplate extends AbstractTemplate
{

	public function generateCode(): ClassType
	{
		/** @var $annotations \Codegen\Templates\Setter\Setter */
		$annotations = $this->getAnnotations();
		$classType = $this->getClassType();
		$className = $classType->getName();

		$this->checkRequiredParam('type');

		$paramType = $annotations->type;
		$default = isset($annotations->default) ? $annotations->default : 'valueNotSet';
		$nullable = isset($annotations->nullable) ? $annotations->nullable : FALSE;
		$method = 'set' . ucfirst($this->getPropertyName());

		$this->checkMethodExistence($method);

		$methodType = $classType->addMethod($method)
			->addComment('Generated by Codegen.')
			->addComment(sprintf('@return %s', $className))
			->setVisibility('public')
			->setReturnType('self')
			->setBody(sprintf("\$this->%s = \$value;\n\nreturn \$this;", $this->getPropertyName()));

		$parameterType = $methodType->addParameter('value')
			->setTypeHint($paramType)
			->setNullable($nullable);

		if ($default !== 'valueNotSet') {
			$parameterType->setDefaultValue($default);
		}

		return $classType;
	}

}