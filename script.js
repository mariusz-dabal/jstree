//FETCH 
function fetch(url, callback) {
    $.ajax({
        async: true,
        type: "GET",
        url: url,
        dataType: "json",
        success: function (json) {
            callback(json);

        },

        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

// POPULATE TREE
function createJSTree(jsondata) {
    $('#jstree').jstree({
        'core': {
            "check_callback": true,
            'data': jsondata
        },
        "plugins": ["contextmenu"],
        "contextmenu": {
            "items": function ($node) {
                let tree = $("#jstree").jstree(true);
                return {
                    "Create": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Nowy",
                        "action": function (obj) {
                            $node = tree.create_node($node);
                            tree.edit($node);
                        }
                    },
                    "Rename": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Zmień nazwę",
                        "action": function (obj) {
                            tree.edit($node);
                        }
                    },
                    "Remove": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Usuń",
                        "action": function (obj) {
                            tree.delete_node($node);
                        }
                    },
                    "Edit": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Edytuj",
                        "action": false,
                        "submenu": {
                            "Cut": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "Wytnij",
                                "action": function (obj) {
                                    tree.cut($node);
                                }
                            },
                            "Paste": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "Wklej",
                                "action": function (obj) {
                                    tree.paste($node);
                                }
                            }
                        }
                    }
                }
            }
        }
    });
}


// CREATE AND RENAME
$('#jstree').on("rename_node.jstree", function (e, data) {
    $.get('operations.php?operation=create_node', {
        'id': data.node.id,
        'parent': data.node.parent,
        'text': data.node.text
    }).done(id => {
        data.instance.set_id(data.node, id);
    })
        .fail(() => {
            data.instance.refresh();
        });
});

// DELETE
$('#jstree').on("delete_node.jstree", function (e, data) {
    $.get('operations.php?operation=delete_node', {
        'id': data.node.id,
    }).fail((e) => {
        console.log(e.responseText);
        data.instance.refresh();
    });
});

// Paste
$('#jstree').on("paste.jstree", function (e, data) {
    $.get('operations.php?operation=paste_node', {
        'id': data.node[0].id,
        'parent': data.parent
    }).done(d => console.log(d)).fail((e) => {
        console.log(e.responseText);
        data.instance.refresh();
    });
});

const load = () => {
    fetch("http://localhost/tree/getdata.php", createJSTree);
}

window.onload = load;
