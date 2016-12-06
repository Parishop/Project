<?php
namespace Project\Framework;

/**
 * Class Extensions
 * @package Parishop\Framework
 * @since   1.0
 */
class Extensions extends \PHPixie\Framework\Extensions
{
    /**
     * @type Builder
     * @since 1.0
     */
    protected $builder;

    /**
     * Провайдеры авторизации
     * @return array
     * @since 1.0
     */
    public function authProviderBuilders()
    {
        return array_merge(
            parent::authProviderBuilders(), [
                // Тут Добавляются собственные Провайдеры авторизации
            ]
        );
    }

    /**
     * Расширения для представления
     * @return array
     * @since 1.0
     */
    public function templateExtensions()
    {
        return array_merge(
            parent::templateExtensions(), [
                new \Project\Document($this->builder),
                new \Project\Messages($this->builder->context()),
                new \Project\Images($this->builder->assets()->webRoot()->path(), 'images', $this->builder->configuration()->config()->arraySlice()),
                // Тут Добавляются собственные Расширения для представления
            ]
        );
    }

    /**
     * Форматы шаблонов
     * @return array
     * @since 1.0
     */
    public function templateFormats()
    {
        return array_merge(
            parent::templateFormats(), [
                // Тут Добавляются собственные Форматы шаблонов
            ]
        );
    }

}

