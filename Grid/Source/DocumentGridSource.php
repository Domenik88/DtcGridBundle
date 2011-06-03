<?php
namespace Dtc\GridBundle\Grid\Source;

use Dtc\GridBundle\Grid\Column\GridColumn;

use Doctrine\ODM\MongoDB\DocumentManager;

class DocumentGridSource
	extends AbstractGridSource
{
	protected $dm;
	protected $documentName;
	protected $repository;

	public function __construct(DocumentManager $dm, $documentName, $serviceId)
	{
		$this->dm = $dm;
		$this->repository = $dm->getRepository($documentName);
		$this->documentName = $documentName;
		$this->id = base64_encode($serviceId);

		// Auto set columns
		$this->setColumns($this->getReflectionColumns());
	}

	protected function getCursor() {
		return $this->repository->findBy(
			$this->filter,
			$this->orderBy,
			$this->limit,
			$this->offset
		);
	}

	/**
	 *
	 * @return ClassMetadata
	 */
	public function getClassMetadata() {
		$metaFactory = $this->dm->getMetadataFactory();
		$classInfo = $metaFactory->getMetadataFor($this->documentName);

		return $classInfo;
	}

	/**
	 * Generate Columns based on document's Metadata
	 */
	public function getReflectionColumns() {
		$metaClass = $this->getClassMetadata();

		$columns = array();
		foreach ($metaClass->fieldMappings as $fieldInfo) {
			$field = $fieldInfo['fieldName'];
			if (isset($fieldInfo['options']) && isset($fieldInfo['options']['label']))
			{
				$label = $fieldInfo['options']['label'];
			}
			else
			{
				$label = $this->fromCamelCase($field);
			}

			$columns[$field] = new GridColumn($field, $label);
		}

		return $columns;
	}

	protected function fromCamelCase($str) {
		$func = function($str) {
			return ' ' . $str[0];
		};

		$value = preg_replace_callback('/([A-Z])/', $func, $str);
		$value = ucfirst($value);

		return $value;
	}

	public function getCount() {
		return $this->getCursor()->count();
	}

	public function getRecords() {
		return $this->getCursor();
	}
}