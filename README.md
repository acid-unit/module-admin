# About

[🧪Acid Unit](https://acid.7prism.com/)
Magento Open Source extension designed to enhance
the admin panel with useful tweaks and configurations.

This module also serves as a helper for admin-related configurations and menu management. 
It is automatically installed as a dependency for all Acid Unit extensions.

# Admin Panel Tweaks

## Configuration-Based Tweaks

### WYSIWYG Editor for PageBuilder `HTML Code` Element

#### Description

By enabling this option, users can edit content visually instead of working directly with raw HTML, 
reducing errors and making content management easier.

The `HTML Code` element is a powerful tool, but non-technical users may find it difficult to use.
This configuration allows admin users to toggle the WYSIWYG editor inside the element for quicker HTML editing.

#### Toggle Config

`Stores > Settings > Configuration > General > Content Management > WYSIWYG Options`

![Toggle Pagebuilder Editor](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/toggle.png?raw=true)

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/pagebuilder-editor-demo.gif?raw=true)

## Code-Based Tweaks

### WYSIWYG Editor for Textarea Fields

#### Description

The ability to define and edit HTML for small content blocks makes content management more 
flexible and efficient. PageBuilder is a great but massive tool which does not cover all
the content editing requirements. The plain WYSIWYG editor is ideal for 
small to medium content blocks.

#### Implementation

Toggle button should be defined as a new `<field>` element with the following inner structure:
- `<attribute type="target_field">{section_id}_{group_id}_{field_id}</attribute>`
- `<frontend_model>AcidUnit\Admin\Block\Adminhtml\System\Config\Form\Field\ToggleEditorButton</frontend_model>`

Use the code below as an example.

```xml
<!-- VendorName/VendorModule/etc/adminhtml/system.xml -->

<section id="page">
    ...
    <group id="form">
        ...
        <field id="editable_field" type="textarea" sortOrder="10">
            ...
        </field>
        
        <field id="editable_field_toggle_editor" sortOrder="15">
            <attribute type="target_field">page_form_editable_field</attribute>
            <frontend_model>AcidUnit\Admin\Block\Adminhtml\System\Config\Form\Field\ToggleEditorButton</frontend_model>
        </field>
    </group>
</section>
```

**NOTE**: `sortOrder` attribute for toggle button should be higher for the button to render right under the field.

#### Demo

![Admin Field Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/admin-field-editor-demo.gif?raw=true)

### Table Field

#### Description

The Table Field feature allows multiple values, to be stored and edited efficiently 
in a single field. 
This is ideal for scenarios like managing dynamic redirects, 
tracking event-based actions, or defining structured content blocks.

#### Implementation

For table field to render, you should set `<frontend_model>` and `<backend_model>` classes for target `<field>` element:

```xml
<!-- VendorName/VendorModule/etc/adminhtml/system.xml -->

<section id="...">
    ...
    <group id="...">
        ...
        <field id="...">
            <label>...</label>
            <frontend_model>VendorName\VendorModule\Block\Adminhtml\Form\Field\YourCustomTableField</frontend_model>
            <backend_model>VendorName\VendorModule\Model\System\Config\Backend\YourCustomTableField</backend_model>
        </field>
    </group>
</section>
```

Then, create virtual classes for frontend and backend models and helper:

```xml
<!-- VendorName/VendorModule/etc/adminhtml/di.xml -->

<!-- Backend Model Helper -->
<virtualType name="VendorName\VendorModule\Helper\YourCustomTableField"
             type="AcidUnit\Admin\Helper\AdminTableField">
    <arguments>
        <argument name="tableFields" xsi:type="array">

            <!-- items for 'tableFields' argument here represents column id's of your table -->
            <item name="enabled" xsi:type="string">enabled</item>
            <item name="url" xsi:type="string">url</item>
            <item name="event" xsi:type="string">event</item>
        </argument>
    </arguments>
</virtualType>

<!-- Backend Model defined in system.xml -->
<virtualType name="VendorName\VendorModule\Model\System\Config\Backend\YourCustomTableField"
             type="AcidUnit\Admin\Model\System\Config\Backend\AdminTableField">
    <arguments>

        <!-- backend model helper virtual class should be passed as an argument with 'helper' name -->
        <argument name="helper" xsi:type="object">VendorName\VendorModule\Helper\YourCustomTableField</argument>
    </arguments>
</virtualType>

<!-- Frontend Model defined in system.xml -->
<virtualType name="VendorName\VendorModule\Block\Adminhtml\Form\Field\YourCustomTableField"
             type="AcidUnit\Admin\Block\Adminhtml\Form\Field\AdminTableField">
    <arguments>
        <argument name="tableFields" xsi:type="array">

            <!-- items for 'tableFields' argument here represents columns of your table -->
            <!-- for item names use id's defined in backend model helper -->
            <item name="enabled" xsi:type="array">

                <!-- 'label' item should have the column label -->
                <item name="label" xsi:type="string">Enabled</item>

                <!-- for the dropdown field you should define 'renderer' item and pass dropdown renderer class -->
                <!-- it will be implemented below -->
                <item name="renderer" xsi:type="object">VendorName\VendorModule\Block\Adminhtml\Form\Field\Yesno</item>
            </item>
            <item name="url" xsi:type="array">
                <item name="label" xsi:type="string">Page URL</item>

                <!-- for the text input field you should define 'class' item with HTML class list -->
                <!-- 'admin__control-text' is a required class; use 'required-entry' class if the field should always have a value to be saved -->
                <item name="class" xsi:type="string">admin__control-text required-entry</item>
            </item>
            <item name="event" xsi:type="array">
                <item name="label" xsi:type="string">Event Name</item>
                <item name="class" xsi:type="string">admin__control-text required-entry</item>
            </item>
        </argument>

        <!-- 'buttonLabel' argument is used to set a text for the button to create a new row -->
        <argument name="buttonLabel" xsi:type="string">Add Custom Page</argument>
    </arguments>
</virtualType>
```

And add dropdown renderer:

```php
<?php
/** VendorName\VendorModule\Block\Adminhtml\Form\Field\Yesno.php */

namespace VendorName\VendorModule\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class Yesno extends Select
{
    /**
     * @param string $value
     * @return mixed
     */
    public function setInputName(string $value): mixed
    {
        return $this->setName($value);
    }

    /**
     * Dropdown options are set in this method
     * First addOption() parameter is an option value, second parameter is an option label
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->addOption('1', __('Yes'));
            $this->addOption('0', __('No'));
        }

        return parent::_toHtml();
    }
}
```

After declaring virtual classes, make sure you compile them and deploy static content if necessary: 

```shell
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/table-field/demo.gif?raw=true)


#### Additional

The table field value is stored as a stringified object and can be handled like any regular admin text field.
Use `JSON.parse` on the frontend or `\Magento\Framework\Serialize\Serializer\Json::serialize` on the backend to
parse and manipulate the data.

# Installation

This module is installed automatically when using any Acid Unit extensions. 
If installing manually, use:

```shell
composer require acid-unit/module-admin
```

After that make sure your module is registered:

```shell
bin/magento module:enable AcidUnit_Admin
```

# Requirements

This module is compatible with Magento Open Source and Adobe Commerce versions >=`2.4.4`
and requires `PHP 8.1` or later.

<small>✅ Verified on Adobe Commerce 2.4.7-p3 with PHP 8.3</small>
