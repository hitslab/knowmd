<?php


namespace App\Service;


class ExtendedParsedown extends \Parsedown
{
    protected function blockHeader($line)
    {
        $block = parent::blockHeader($line);
        $block['element']['attributes'] = [
            'id' => $block['element']['text'],
        ];
        return $block;
    }
}
