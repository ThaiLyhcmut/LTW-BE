<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ClassUtils;

/**
 * Trait FocalGravityTrait
 */
trait FocalGravityTrait
{
    /**
     * Sets the focal gravity for resizing.
     *
     *
     * @return $this
     */
    public function gravity(mixed $focalGravity): static
    {
        return $this->addQualifier(
            ClassUtils::verifyInstance($focalGravity, GravityQualifier::class, FocalGravity::class)
        );
    }
}
