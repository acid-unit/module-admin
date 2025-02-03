# About

A **Magento Open Source** extension designed to enhance the **admin panel experience** 
with additional tweaks and configuration options.

This module:<br>
✅ **Enhances the admin interface** with additional configuration tools.<br>
✅ Improves menu management for better navigation within Acid Unit extensions.<br>
✅ Acts as a dependency for all Acid Unit extensions, ensuring consistent admin functionality.

Tweaks summary table:

* [Configuration-Based Tweaks](#configuration-based-tweaks)
    + [WYSIWYG for PageBuilder `HTML Code` Element](#wysiwyg-editor-for-pagebuilder--html-code--element)
* [Code-Based Tweaks](#code-based-tweaks)
    + [WYSIWYG for Textarea Fields](#wysiwyg-editor-for-textarea-fields)
    + [Table Field](#table-field)

# Admin Panel Tweaks

## Configuration-Based Tweaks

### WYSIWYG Editor for PageBuilder `HTML Code` Element

#### Description

Enabling this option allows users to edit content **visually** instead of dealing with raw HTML, 
reducing errors and **improving content management efficiency**.

Since the `HTML Code` element is powerful yet complex, this configuration 
lets **admin users toggle the WYSIWYG editor** inside the element for **quicker editing**.

#### Toggle Config

`Stores > Settings > Configuration > General > Content Management > WYSIWYG Options`

![Toggle Pagebuilder Editor](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/toggle.png?raw=true)

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/pagebuilder-editor-demo.gif?raw=true)

## Code-Based Tweaks

### WYSIWYG Editor for Textarea Fields

#### Description

Provides **HTML editing** directly within the admin field, 
providing a **lightweight alternative to PageBuilder** for small-to-medium content sections.

#### Implementation

The **toggle button** should be defined as a new `<field>` element with the following structure:

```xml
<!-- VendorName/VendorModule/etc/adminhtml/system.xml -->

<section id="page">
    <group id="form">
        <field id="editable_field" type="textarea" sortOrder="10">...</field>
        
        <field id="editable_field_toggle_editor" sortOrder="15">
            <attribute type="target_field">page_form_editable_field</attribute>
            <frontend_model>AcidUnit\Admin\Block\Adminhtml\System\Config\Form\Field\ToggleEditorButton</frontend_model>
        </field>
    </group>
</section>
```

📌 Ensure the `sortOrder` value for the toggle button is higher than the target field
so that it appears **directly beneath it**.

#### Demo

![Admin Field Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/wysiwyg-editor/admin-field-editor-demo.gif?raw=true)

### Table Field

#### Description

The **Table Field** allows multiple values to be stored **within** a single field, 
making it **ideal** for:<br>
✅ Dynamic redirects<br>
✅ Event-based tracking<br>
✅ Structured content blocks

#### Implementation

To render a **table field**, define the `<frontend_model>` and `<backend_model>` 
classes in your `<field>` element:

```xml
<!-- VendorName/VendorModule/etc/adminhtml/system.xml -->

<section id="...">
    <group id="...">
        <field id="...">
            <label>...</label>
            <frontend_model>VendorName\VendorModule\Block\Adminhtml\Form\Field\YourCustomTableField</frontend_model>
            <backend_model>VendorName\VendorModule\Model\System\Config\Backend\YourCustomTableField</backend_model>
        </field>
    </group>
</section>
```

**Backend & Frontend Virtual Models**

Define virtual classes for **backend**, **frontend**, and **helper models**:

```xml
<!-- VendorName/VendorModule/etc/adminhtml/di.xml -->

<!-- Backend Model Helper -->
<virtualType name="VendorName\VendorModule\Helper\YourCustomTableField"
             type="AcidUnit\Admin\Helper\AdminTableField">
    <arguments>
        <argument name="tableFields" xsi:type="array">

            <!-- items for 'tableFields' argument represent column IDs of your table -->
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

        <!-- backend model helper virtual class 
             should be passed as an argument with 'helper' name -->
        <argument name="helper" xsi:type="object">VendorName\VendorModule\Helper\YourCustomTableField</argument>
    </arguments>
</virtualType>

<!-- Frontend Model defined in system.xml -->
<virtualType name="VendorName\VendorModule\Block\Adminhtml\Form\Field\YourCustomTableField"
             type="AcidUnit\Admin\Block\Adminhtml\Form\Field\AdminTableField">
    <arguments>
        <argument name="tableFields" xsi:type="array">

            <!-- items for 'tableFields' argument here 
                 represents columns of your table -->
            <!-- for item names use id's defined in backend model helper -->
            <item name="enabled" xsi:type="array">

                <!-- 'label' item should have the column label -->
                <item name="label" xsi:type="string">Enabled</item>

                <!-- for the dropdown field you should define 'renderer' item 
                     and pass dropdown renderer class which will be implemented below -->
                <item name="renderer" xsi:type="object">VendorName\VendorModule\Block\Adminhtml\Form\Field\Yesno</item>
            </item>
            <item name="url" xsi:type="array">
                <item name="label" xsi:type="string">Page URL</item>

                <!-- for the text input field you should define 'class' 
                     item with HTML class list -->
                <!-- 'admin__control-text' is a required class; 
                     use 'required-entry' class if the field should be required -->
                <item name="class" xsi:type="string">admin__control-text required-entry</item>
            </item>
            <item name="event" xsi:type="array">
                <item name="label" xsi:type="string">Event Name</item>
                <item name="class" xsi:type="string">admin__control-text required-entry</item>
            </item>
        </argument>

        <!-- 'buttonLabel' argument is used to set a text 
             for the button to create a new row -->
        <argument name="buttonLabel" xsi:type="string">Add Custom Page</argument>
    </arguments>
</virtualType>
```

📌 Note: Ensure all virtual classes are compiled before deployment.

**Dropdown Renderer for Table Fields**

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

📌 Compile & Deploy:

```shell
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

#### Demo

![Pagebuilder Editor Demo](https://github.com/acid-unit/docs/blob/main/admin/table-field/demo.gif?raw=true)


#### Additional Notes

The **Table Field value** is stored as a **stringified object** and can be handled like any regular admin text field.

- On the frontend, use `JSON.parse`.
- On the backend, use `\Magento\Framework\Serialize\Serializer\Json::serialize`.

to parse and manipulate the data.

# Installation

This module is **installed automatically** with any Acid Unit extensions.
If installing manually, use:

```shell
composer require acid-unit/module-admin
```

After installation, enable the module and run `setup:upgrade`:

```shell
bin/magento module:enable AcidUnit_Admin
bin/magento setup:upgrade
```

# Requirements

✅ **Compatible with**: Magento Open Source & Adobe Commerce `>=2.4.4`
✅ Requires `PHP 8.1+`

<small>🛠 **Tested on** Magento Open Source `2.4.7-p3` with `PHP 8.3`</small>
