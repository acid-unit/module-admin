# About

[🧪Acid Unit](https://acid.7prism.com/) 
<span title="Magento">Adobe Commerce</span> extension that provides 
useful things and tweaks for an admin panel.

Additionally, this module is used as a helper admin panel-related module 
with configuration and menu items, and will be pulled automatically 
as a dependency with all Acid Unit extensions.

# Admin Panel Tweaks

## By Config

### WYSIWYG editor for PageBuilder `HTML Code` element

#### Description

`HTML Code` is an awesome element, but some non-tech users can struggle while using it.
This config allows admin users to show/hide WYSIWYG editor inside element fast HTML editing.

#### Toggle Config

`Stores > Settings > Configuration > General > Content Management > WYSIWYG Options`

![Toggle Pagebuilder Editor](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/toggle.png?raw=true)

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/pagebuilder-editor-demo.gif?raw=true)

## By Code

### WYSIWYG editor for textarea fields

#### Description

Having ability to define and edit HTML for small content blocks makes the content management
flexible and efficient. PageBuilder is a great but massive tool which does not cover all
the content editing requirements. While plain WYSIWYG editor does the great job with 
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

### Table field

#### Description

This will be useful to store and edit multiple text and dropdown data into a single field.

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

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/table-field/demo.gif?raw=true)


#### Additional

The value of table field will be stored as a stringified object and should be treated as any other regular admin text field for getting value.
`JSON.parse` can be used on the frontend or `\Magento\Framework\Serialize\Serializer\Json::serialize` on the backend to
parse the value string and work with the data.

# Installation

`composer require acid-unit/module-admin`

# Requirements

As long as you have at least `Adobe Commerce 2.4.4` with running `PHP 8.1` or newer, 
everything should be fine.
