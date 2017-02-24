<?php
namespace Smart\HoverImage\Block\Product;

use Magento\Catalog\Helper\ImageFactory as HelperFactory;

class ImageBuilder extends \Magento\Catalog\Block\Product\ImageBuilder
{
    /**
     * Create image block
     *
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function create()
    {
        /** @var \Magento\Catalog\Helper\Image $helper */
        $helper = $this->helperFactory->create()
            ->init($this->product, $this->imageId);

        $hoverImage = $this->helperFactory->create()
            ->init($this->product, $this->imageId, ['type' => 'hover_image']);

        $template = $helper->getFrame()
            ? 'Smart_HoverImage::product/image.phtml'
            : 'Smart_HoverImage::product/image_with_borders.phtml';

        $imagesize = $helper->getResizedImageInfo();

        $hoverImageUrl = $hoverImage->getUrl();

        /** NOT the best solution, Just a simple workaround when auto search placeholder image from Magento_Catalog */
        if (false == strpos('hover_image.jpg', $hoverImageUrl)) {
            $hoverImageUrl = str_replace('Magento_Catalog', 'Smart_HoverImage', $hoverImageUrl);
        }

        $data = [
            'data' => [
                'template' => $template,
                'image_url' => $helper->getUrl(),
                'hover_image_url' => $hoverImageUrl,
                'width' => $helper->getWidth(),
                'height' => $helper->getHeight(),
                'label' => $helper->getLabel(),
                'ratio' => $this->getRatio($helper),
                'custom_attributes' => $this->getCustomAttributes(),
                'resized_image_width' => !empty($imagesize[0]) ? $imagesize[0] : $helper->getWidth(),
                'resized_image_height' => !empty($imagesize[1]) ? $imagesize[1] : $helper->getHeight(),
            ],
        ];

        return $this->imageFactory->create($data);
    }
}