<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Validation\Html;

interface HtmlAttributesFactoryInterface
{
    /**
     * Create attributes for the given rules.
     *
     * @param string|array $rules
     * @param string $inputType
     * @param string $inputName
     * @param null|string $messageDataAttributeName
     * @return array
     */
    public function createAttributes(
        string|array $rules,
        string $inputType,
        string $inputName,
        null|string $messageDataAttributeName = 'data-validation-messages'
    ): array;
}