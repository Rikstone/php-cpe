<?php

declare(strict_types=1);

namespace Rikstone\Cpe;

use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\LogicalValue;
use Rikstone\Cpe\Common\Part;

final class WellFormedName
{
    public function __construct(
        /**
         * Single letter code that designates the particular platform part that is being identified
         */
        public Part $part {
            get => $this->part;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD describe or identify the person or organization that manufactured or created the product
         */
        public string|LogicalValue $vendor = new Any() {
            get => $this->vendor;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD describe or identify the most common and recognizable title or name of the product
         */
        public string|LogicalValue $product = new Any() {
            get => $this->product;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD be vendor-specific alphanumeric strings characterizing the particular
         * release version of the product
         */
        public string|LogicalValue $version = new Any() {
            get => $this->version;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD be vendor-specific alphanumeric strings characterizing the particular
         * update, service pack, or point release of the product
         */
        public string|LogicalValue $update = new Any() {
            get => $this->update;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD capture edition-related terms applied by the vendor to the product
         */
        public string|LogicalValue $edition = new Any() {
            get => $this->edition;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD characterize how the product is tailored to a particular market or class of end users
         */
        public string|LogicalValue $swEdition = new Any() {
            get => $this->swEdition;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD characterize the software computing environment within which the product operates
         */
        public string|LogicalValue $targetSW = new Any() {
            get => $this->targetSW;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD characterize the instruction set architecture (e.g., x86) on which the
         * product being described or identified by the WFN operates
         */
        public string|LogicalValue $targetHW = new Any() {
            get => $this->targetHW;
            set => $value;
        },

        /**
         * Value for this attribute SHALL be valid language tags as defined by [RFC5646], and SHOULD be used
         * to define the language supported in the user interface of the product being described
         */
        public string|LogicalValue $language = new Any() {
            get => $this->language;
            set => $value;
        },

        /**
         * Values for this attribute SHOULD capture any other general descriptive or identifying information which
         * is vendor- or product-specific and which does not logically fit in any other attribute value
         */
        public string|LogicalValue $other = new Any() {
            get => $this->other;
            set => $value;
        },
    ) {}
}