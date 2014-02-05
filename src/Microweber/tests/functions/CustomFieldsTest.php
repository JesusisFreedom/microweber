<?php

namespace FunctionsTest;


class CustomFieldsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddCustomFields()
    {
        $params = array(
            'title' => 'this-is-my-test-shop-for-custom-fields',
            'content_type' => 'page',
            'is_shop' => 'y',
            // 'debug' => 1,
            'is_active' => 'y');

        //creating our shop page
        $my_shop = save_content($params);


        $params = array(
            'title' => 'this-is-my-test-product-with-custom-fields',
            'content_type' => 'post',
            'subtype' => 'product',
            'parent' => $my_shop,
            // 'debug' => 1,
            'is_active' => 'y');
        //adding a product to our shop page
        $my_product = save_content($params);
        $product = get_content_by_id($my_product);

        $price = rand();
        $saved_fields = array(); //ids of the saved custom fields


        $custom_field = array(
            'field_name' => 'My price',
            'field_value' => $price,
            'field_type' => 'price',
            // 'debug' => 1,
            'content_id' => $my_product);
        //adding a custom field "price" to product
        $saved_fields[] = save_custom_field($custom_field);


        $custom_field = array(
            'field_name' => 'Color',
            'field_value' => array('Red', 'Blue', 'Green'),
            'field_type' => 'dropdown',
            // 'debug' => 1,
            'content_id' => $my_product);
        //adding a custom field "Color" to product
        $saved_fields[] = save_custom_field($custom_field);

        $custom_field = array(
            'field_name' => 'Size',
            'field_value' => array('S', 'M', 'L', 'XL'),
            'field_type' => 'radio',
            // 'debug' => 1,
            'content_id' => $my_product);
        //adding a custom field "Size" to product
        $saved_fields[] = save_custom_field($custom_field);

        $custom_fields = get_custom_fields('content', $my_product, $return_full = true);


        foreach ($custom_fields as $custom_field) {
            //PHPUnit
            $this->assertEquals(true, in_array($custom_field['id'], $saved_fields));
            $this->delete_custom_fields[] = $custom_field['id'];
        }
        $this->assertEquals(true, is_array($product));
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, intval($my_product) > 0);
        $this->assertEquals(true, !empty($saved_fields));
        $this->assertEquals(true, intval($my_shop) > 0);

        $this->delete_content[] = $my_shop;
        $this->delete_content[] = $my_product;
    }

    protected function tearDown()
    {
        if (isset($this->delete_content) and is_array($this->delete_content)) {
            foreach ($this->delete_content as $item) {
                $delete_content = delete_content($item);
                $check_deleted = get_content_by_id($item);

                //PHPUnit
                $this->assertEquals(true, is_array($delete_content));
                $this->assertEquals(true, !is_array($check_deleted));
            }
        }


        if (isset($this->delete_custom_fields) and is_array($this->delete_custom_fields)) {
            foreach ($this->delete_custom_fields as $item) {
                $delete_content = delete_custom_field($item);
                $check_deleted = get_custom_field_by_id($item);

                //PHPUnit
                $this->assertEquals(true, $delete_content);
                $this->assertEquals(true, !is_array($check_deleted));
            }
        }


    }


}