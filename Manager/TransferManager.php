<?php

namespace HeVinci\CompetencyBundle\Manager;

use HeVinci\CompetencyBundle\Transfer\DataConflictValidator;
use HeVinci\CompetencyBundle\Transfer\DataConstraintValidator;
use HeVinci\CompetencyBundle\Transfer\JsonValidator;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("hevinci.competency.transfer_manager")
 */
class TransferManager
{
    const ERR_TYPE_NONE = 'err_type_none';
    const ERR_TYPE_JSON = 'err_type_json';
    const ERR_TYPE_SCHEMA = 'err_type_schema';
    const ERR_TYPE_INTERNAL = 'err_type_internal';
    const ERR_TYPE_CONFLICT = 'err_type_conflict';

    private $jsonValidator;
    private $dataValidator;
    private $conflictValidator;

    /**
     * @DI\InjectParams({
     *     "jsonValidator"      = @DI\Inject("hevinci.competency.json_validator"),
     *     "dataValidator"      = @DI\Inject("hevinci.competency.data_validator"),
     *     "conflictValidator"  = @DI\Inject("hevinci.competency.data_conflict_validator")
     * })
     *
     * @param JsonValidator             $jsonValidator
     * @param DataConstraintValidator   $dataValidator
     * @param DataConflictValidator     $conflictValidator
     */
    public function __construct(
        JsonValidator $jsonValidator,
        DataConstraintValidator $dataValidator,
        DataConflictValidator $conflictValidator
    )
    {
        $this->jsonValidator = $jsonValidator;
        $this->dataValidator = $dataValidator;
        $this->conflictValidator = $conflictValidator;
    }

    /**
     * Validates a JSON representation of a competency framework contained
     * in a file. Returns an array containing:
     *
     * 1) The type of the errors returned (see class constants)
     * 2) An array containing error message strings
     *
     * @param string $frameworkFile
     * @return array
     * @throws \RuntimeException if the file doesn't exist
     */
    public function validate($frameworkFile)
    {
        if (!file_exists($frameworkFile)) {
            throw new \RuntimeException("'{$frameworkFile}' is not a valid file");
        }

        $framework = json_decode(file_get_contents($frameworkFile));

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'type' => self::ERR_TYPE_JSON,
                'errors' => [json_last_error_msg()]
            ];
        }

        if (count($errors = $this->jsonValidator->validate($framework))) {
            return [
                'type' => self::ERR_TYPE_SCHEMA,
                'errors' => array_map(function ($error) {
                    return sprintf(
                        '%s%s',
                        $error['message'],
                        $error['property'] !== '' ? " (path: {$error['property']})" : ''
                    );
                }, $errors)
            ];
        }

        if (count($errors = $this->dataValidator->validate($framework))) {
            return [
                'type' => self::ERR_TYPE_INTERNAL,
                'errors' => $errors
            ];
        }

        if (count($errors = $this->conflictValidator->validate($framework))) {
            return [
                'type' => self::ERR_TYPE_CONFLICT,
                'errors' => $errors
            ];
        }

        return [
            'type' => self::ERR_TYPE_NONE,
            'errors' => []
        ];
    }

    public function import($frameworkFile)
    {

    }
}
