CheckBoxTree
============

Form Component for Nette framework


##Application
============

Register to form:
app/bootstrap.php

\krejcon3\CheckboxTree::register();


##Using
======

```
$form->addCheckboxTree(
    "cbtree",
    "CheckboxTree",
    array(
        1 => "item1",
        2 => "item2",
        3 => "item3",
        "noid1" => array(
            4 => "item4",
            5 => "item5",
        ),
        6 => "item6"
    )
);
```

input is multidimensional array<br>
output si simple array of keys (ids),<br>
id by the next level (noid1 in example) may be anything you want...<br>


