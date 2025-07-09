<?php

namespace Embed\Adapters;

use Embed\Http\Response;
use Embed\Utils;

/**
 * Adapter to hardcode the embed code for Tableau public.
 */
class Tableau extends Webpage
{
    /**
     * {@inheritdoc}
     */
    public static function DoNotUSEcheck(Response $response)
    {
        // https://public.tableau.com/views/NAME_OF_VIEW/SubName?:language=en-US&:display_count=n&:origin=viz_share_link
        return $response->isValid() && $response->getUrl()->match([
            '*.public.tableau.com/views/*/*',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $this->width = '100%';
        $this->height = '60vh';

        // $this->url ends up not being the same one Tableau offers when you
        // copy the share link.
        $embed_url = $this->response->getStartingUrl() . '&:showVizHome=no&:embed=true';

        return Utils::iframe($embed_url);
    }
}
