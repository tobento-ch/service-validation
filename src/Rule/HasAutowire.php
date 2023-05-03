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

namespace Tobento\Service\Validation\Rule;

use Tobento\Service\Autowire\Autowire;

/**
 * HasAutowire
 */
trait HasAutowire
{
    /**
     * @var null|Autowire
     */
    protected null|Autowire $autowire = null;

    /**
     * Sets the autowire.
     * 
     * @param Autowire $autowire
     * @return static $this
     */
    public function setAutowire(Autowire $autowire): static
    {
        $this->autowire = $autowire;
        return $this;
    }
    
    /**
     * Returns the autowire.
     * 
     * @return null|Autowire
     */
    public function autowire(): null|Autowire
    {
        return $this->autowire;
    }
}