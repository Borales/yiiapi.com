<?php
/**
 * @var $this DocController
 * @var $items array
 * @var $packs
 */

echo CHtml::openTag('div', array('id' => 'search'));
echo CHtml::tag(
    'input',
    array(
        'type' => 'search',
        'placeholder' => 'Search',
        'results' => 10,
        'id' => 'search-field',
        'autocomplete' => 'off',
        'autosave' => 'searchdoc'
    ),
    '',
    false
);
echo CHtml::closeTag('div');

echo CHtml::openTag('ul', array('id' => 'static-list'));

foreach( $packs as $packName=>$packItems ) {
    echo CHtml::openTag('li', array('class' => 'category'));
    echo CHtml::tag('span', array(), $packName);
    if( $packItems ) {
        echo CHtml::openTag('ul');
        foreach($packItems as $packItem) {
            $title = CHtml::tag('span', array('class' => 'searchable'), $packItem);
            $description = CHtml::tag('span', array('class' => 'desc'), $this->getSectionDescription($packItem));
            $link = CHtml::link($title.' '.$description, $this->createUrl('doc/view', array('name' => $packItem)));

            echo CHtml::tag('li', array('class' => 'sub'), $link);
        }
        echo CHtml::closeTag('ul');
    }
    echo CHtml::closeTag('li');
}
echo CHtml::closeTag('ul');
