<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Skyline\CMS\Controller;


use Skyline\Application\Controller\AbstractActionController;
use Skyline\Render\Exception\TemplateNotFoundException;
use Skyline\Router\Description\ActionDescriptionInterface;

abstract class AbstractTemplateActionController extends AbstractActionController
{
    /**
     * @inheritDoc
     */
    protected function getActionMethodName(ActionDescriptionInterface $actionDescription): string
    {
        return "directTemplateAction";
    }

    /**
     * Builtin method to resolve template action method names
     *
     * @param ActionDescriptionInterface $actionDescription
     */
    public function directTemplateAction(ActionDescriptionInterface $actionDescription) {
        $templateName = $this->resolveTemplateName($actionDescription);
        if($templateName) {
            $layout = $this->getMainLayoutName($actionDescription);
            $tmap = $this->mapTemplate($actionDescription, $templateName);

            $this->renderTemplate($layout, $tmap);
        } else {
            $e = new TemplateNotFoundException("Could not resolve template for method description %s", 404, NULL, $actionDescription->getMethodName());
            $e->setTemplateID($actionDescription->getMethodName());
            throw $e;
        }
    }

    /**
     * @param ActionDescriptionInterface $actionDescription
     * @return string
     * @throws TemplateNotFoundException
     */
    protected function resolveTemplateName(ActionDescriptionInterface $actionDescription): string {
        $method = $actionDescription->getMethodName();
        if(preg_match("/^(.*?)Action$/i", $method, $ms))
            return $ms[1];

        return "";
    }

    /**
     * Decide which layout should be used to render the current action
     *
     * @param ActionDescriptionInterface $actionDescription
     * @return string
     */
    protected function getMainLayoutName(ActionDescriptionInterface $actionDescription): string {
        return "main";
    }

    /**
     *
     * @param ActionDescriptionInterface $actionDescription
     * @param $templateName
     * @return array
     */
    protected function mapTemplate(ActionDescriptionInterface $actionDescription, $templateName): array {
        return [
            "Content" => $templateName
        ];
    }
}