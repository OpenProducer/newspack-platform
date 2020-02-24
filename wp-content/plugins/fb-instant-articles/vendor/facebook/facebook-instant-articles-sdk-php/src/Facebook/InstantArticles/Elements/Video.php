<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Elements;

use Facebook\InstantArticles\Validators\Type;

/**
 * Class Video
 * This element Class is the video for the article.
 * Also consider to use one of the other media types for an article:
 * <ul>
 *     <li>Audio</li>
 *     <li>Image</li>
 *     <li>Slideshow</li>
 *     <li>Map</li>
 * </ul>
 *
 * Example:
 *  <figure>
 *      <video>
 *          <source src="http://mydomain.com/path/to/video.mp4" type="video/mp4" />
 *      </video>
 *  </figure>
 *
 * @see Audio
 * @see Image
 * @see Slideshow
 * @see Map
 * @package Facebook\InstantArticle\Elements
 */
class Video extends Element implements ChildrenContainer
{
    const ASPECT_FIT = 'aspect-fit';
    const ASPECT_FIT_ONLY = 'aspect-fit-only';
    const FULLSCREEN = 'fullscreen';
    const NON_INTERACTIVE = 'non-interactive';

    const LOOP = 'loop';
    const DATA_FADE = 'data-fade';

    /**
     * @var Caption The caption for Video
     */
    private $caption;

    /**
     * @var string The string url for the video hosted on web that will be shown
     * on the article
     */
    private $url;

    /**
     * @var string The video content type. Default: "video/mp4"
     */
    private $contentType;

    /**
     * @var boolean Makes the video the cover on news feed.
     *
     * @see {link:https://developers.facebook.com/docs/instant-articles/reference/feed-preview}
     */
    private $isFeedCover;

    /**
     * @var string Content that will be shown on <cite>...</cite> tags.
     */
    private $attribution;

    /**
     * @var string The picture size for the video.
     *
     * @see Video::ASPECT_FIT
     * @see Video::ASPECT_FIT_ONLY
     * @see Video::FULLSCREEN
     * @see Video::NON_INTERACTIVE
     */
    private $presentation;

    /**
     * @var GeoTag The json geotag content inside the script geotag
     */
    private $geoTag;

    /**
     * @var string URL for the placeholder Image that will be placed while video not loaded.
     */
    private $imageURL;

    /**
     * @var boolean Default true, so every video will autoplay.
     */
    private $isAutoplay = true;

    /**
     * @var boolean Default false, so every video will have no controls.
     */
    private $isControlsShown = false;

    private function __construct()
    {
    }

    /**
     * Factory method
     *
     * @return Video the new instance from Video
     */
    public static function create()
    {
        return new self();
    }

    /**
     * This sets figcaption tag as documentation. It overrides all sets
     * made with Caption.
     *
     * @param Caption $caption the caption the video will have
     * @see Caption.
     * @return $this
     */
    public function withCaption($caption)
    {
        Type::enforce($caption, Caption::getClassName());
        $this->caption = $caption;

        return $this;
    }

    /**
     * Sets the URL for the video. It is REQUIRED.
     *
     * @param string $url The url of video. Ie: http://domain.com/video.mp4
     *
     * @return $this
     */
    public function withURL($url)
    {
        Type::enforce($url, Type::STRING);
        $this->url = $url;

        return $this;
    }

    /**
     * Sets the aspect ration presentation for the video.
     *
     * @param string $presentation one of the constants ASPECT_FIT, ASPECT_FIT_ONLY, FULLSCREEN or NON_INTERACTIVE
     *
     * @see Video::ASPECT_FIT
     * @see Video::ASPECT_FIT_ONLY
     * @see Video::FULLSCREEN
     * @see Video::NON_INTERACTIVE
     *
     * @return $this
     */
    public function withPresentation($presentation)
    {
        Type::enforceWithin(
            $presentation,
            [
                Video::ASPECT_FIT,
                Video::ASPECT_FIT_ONLY,
                Video::FULLSCREEN,
                Video::NON_INTERACTIVE
            ]
        );
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Makes like enabled for this video.
     *
     * @return $this
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function enableLike()
    {
        return $this;
    }

    /**
     * Makes like disabled for this video.
     *
     * @return $this
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function disableLike()
    {
        return $this;
    }

    /**
     * Makes comments enabled for this video.
     *
     * @return $this
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function enableComments()
    {
        return $this;
    }

    /**
     * Makes comments disabled for this video.
     *
     * @return $this
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function disableComments()
    {
        return $this;
    }

    /**
     * Enables the video controls
     *
     * @return $this
     */
    public function enableControls()
    {
        $this->isControlsShown = true;

        return $this;
    }

    /**
     * Disable the video controls
     *
     * @return $this
     */
    public function disableControls()
    {
        $this->isControlsShown = false;

        return $this;
    }

    /**
     * Enables the video autoplay
     *
     * @return $this
     */
    public function enableAutoplay()
    {
        $this->isAutoplay = true;

        return $this;
    }

    /**
     * Disable the video autoplay
     *
     * @return $this
     */
    public function disableAutoplay()
    {
        $this->isAutoplay = false;

        return $this;
    }

    /**
     * Makes video be the cover on newsfeed
     *
     * @return $this
     */
    public function enableFeedCover()
    {
        $this->isFeedCover = true;

        return $this;
    }

    /**
     * Removes video from cover on newsfeed (and it becomes the og:image that was already defined on the link)
     *
     * @return $this
     */
    public function disableFeedCover()
    {
        $this->isFeedCover = false;

        return $this;
    }


    /**
     * @param string $contentType content type of the video. Ex: "video/mp4"
     *
     * @return $this
     */
    public function withContentType($contentType)
    {
        Type::enforce($contentType, Type::STRING);
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Sets the geotag on the video.
     *
     * @see {link:http://geojson.org/}
     *
     * @param string $geoTag
     *
     * @return $this
     */
    public function withGeoTag($geoTag)
    {
        Type::enforce($geoTag, [Type::STRING, GeoTag::getClassName()]);
        if (Type::is($geoTag, Type::STRING)) {
            $this->geoTag = GeoTag::create()->withScript($geoTag);
        } elseif (Type::is($geoTag, GeoTag::getClassName())) {
            $this->geoTag = $geoTag;
        }

        return $this;
    }


    /**
     * Sets the attribution string
     *
     * @param string $attribution The attribution text
     *
     * @return $this
     */
    public function withAttribution($attribution)
    {
        Type::enforce($attribution, Type::STRING);
        $this->attribution = $attribution;

        return $this;
    }

    /**
     * @return Caption gets the caption obj
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return string URL gets the image url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string The content-type of video
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return boolean tells if the like button is enabled
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function isLikeEnabled()
    {
        return false;
    }

    /**
     * @return boolean tells if the autoplay is enabled
     */
    public function isAutoplay()
    {
        return $this->isAutoplay;
    }

    /**
     * @return boolean tells if the comments widget is enabled
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public function isCommentsEnabled()
    {
        return false;
    }

    /**
     * @return boolean tells if the controls will be shown
     */
    public function isControlsShown()
    {
        return $this->isControlsShown;
    }

    /**
     * @return string one of the constants ASPECT_FIT, ASPECT_FIT_ONLY, FULLSCREEN or NON_INTERACTIVE
     *
     * @see Video::ASPECT_FIT
     * @see Video::ASPECT_FIT_ONLY
     * @see Video::FULLSCREEN
     * @see Video::NON_INTERACTIVE
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @return GeoTag The geotag content
     */
    public function getGeotag()
    {
        return $this->geoTag;
    }

    /**
     * Modify the default setup to enable/disable likes in videos
     *
     * WARNING this is not Thread-safe, so if you are using pthreads or any other multithreaded engine,
     * this might not work as expected. (you will need to set this in all working threads manually)
     * @param boolean $enabled inform true to enable likes on videos per default or false to disable like on videos.
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public static function setDefaultLikeEnabled($enabled)
    {
    }

    /**
     * Modify the default setup to enable/disable comments in videos
     *
     * WARNING this is not Thread-safe, so if you are using pthreads or any other multithreaded engine,
     * this might not work as expected. (you will need to set this in all working threads manually)
     * @param boolean $enabled inform true to enable comments on videos per default or false to disable commenting on videos.
     * @deprecated This feature has been deprecated as InstantArticles doesn't support likes, comments and shares to individual media content.
     */
    public static function setDefaultCommentEnabled($enabled)
    {
    }


    /**
     * Structure and create the full Video in a XML format DOMElement.
     *
     * @param \DOMDocument $document where this element will be appended. Optional
     *
     * @return \DOMElement
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        $element = $document->createElement('figure');

        // Presentation
        if ($this->presentation) {
            $element->setAttribute('data-mode', $this->presentation);
        }

        // Poster frame / Image placeholder
        if ($this->imageURL) {
            $imageElement = $document->createElement('img');
            $imageElement->setAttribute('src', $this->imageURL);
            $element->appendChild($imageElement);
        }

        if ($this->isFeedCover) {
            $element->setAttribute('class', 'fb-feed-cover');
        }

        // URL markup required
        if ($this->url) {
            $videoElement = $document->createElement('video');
            if (!$this->isAutoplay) {
                $videoElement->setAttribute('data-fb-disable-autoplay', 'data-fb-disable-autoplay');
            }
            if ($this->isControlsShown) {
                $videoElement->setAttribute('controls', 'controls');
            }
            $sourceElement = $document->createElement('source');
            $sourceElement->setAttribute('src', $this->url);
            if ($this->contentType) {
                $sourceElement->setAttribute('type', $this->contentType);
            }
            $videoElement->appendChild($sourceElement);
            $element->appendChild($videoElement);
        }

        // Caption markup optional
        Element::appendChild($element, $this->caption, $document);

        // Geotag markup optional
        Element::appendChild($element, $this->geoTag, $document);

        // Attribution Citation
        if ($this->attribution) {
            $attributionElement = $document->createElement('cite');
            $attributionElement->appendChild($document->createTextNode($this->attribution));
            $element->appendChild($attributionElement);
        }

        return $element;
    }

    /**
     * Overrides the Element::isValid().
     *
     * @see Element::isValid().
     * @return true for valid Video that contains not empty url, false otherwise.
     */
    public function isValid()
    {
        return !Type::isTextEmpty($this->url);
    }

    /**
     * Implements the ChildrenContainer::getContainerChildren().
     *
     * @see ChildrenContainer::getContainerChildren().
     * @return array of Elements contained by Video.
     */
    public function getContainerChildren()
    {
        $children = array();

        if ($this->caption) {
            $children[] = $this->caption;
        }

        // Geotag markup optional
        if ($this->geoTag) {
            $children[] = $this->geoTag;
        }

        return $children;
    }
}
