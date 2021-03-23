function setupSubItemCreation(prefix) {



    var subItemTemplate = {
        "o:resource_class": {
            "@id": "http://localhost/api/resource_classes/",
            "o:id": 113
        },
        "o:resource_template": {
            "@id": "http://localhost/api/resource_templates/",
            "o:id": 6
        }/*,
        "dcterms:title": [
            {
                "type": "literal",
                "property_id": 1,
                "is_public": true,
                "@value": "Example First Title"
            }
        ],
        "mp:hatElement": [
            {
                "type": "literal",
                "property_id": 219,
                "is_public": true,
                "@value": "Element"
            }
        ],
        "mp:hatFunderfassung": [
            {
                "type": "resource:item",
                "property_id": 207,
                "is_public": true,
                "@id": "id",
                "value_resource_id": 10
            }
        ]*/
    }

    // api credentials
    var keyIdentity = 'Mfi7CrxnEEul7ttDfGY0kxs6IiDCRJLh';
    var keyCredential = 'Q4KWHJzPPLf3Ex3npVuJ51QpMDMEohQ0';

    var url = window.location.href;
    var urlAdmin = url.split("/admin/item")[0];
    var itemId = url.split("/admin/item/")[1];

    let connectingPropertyName = $ ('#'+prefix+'_connectingProperty').val();
    let connectingPropertyId = $ ('#'+prefix+'_connectingPropertyId').val();
    let labelPropertyName = $ ('#'+prefix+'_labelProperty').val();
    let labelPropertyId = $ ('#'+prefix+'_labelPropertyId').val();
    let codePropertyName = $ ('#'+prefix+'_codeProperty').val();
    let codePropertyId = $ ('#'+prefix+'_codePropertyId').val();
    let resourceClassId = $ ('#'+prefix+'_resourceClassId').val();
    let resourceTemplateId = $ ('#'+prefix+'_resourceTemplateId').val();
    var codeValue = $ ('#'+prefix+'_codePropertyValue').val();
    console.log(codeValue);

    var subItem = JSON.parse(JSON.stringify(subItemTemplate));

    subItem["o:resource_class"]["o:id"] = resourceClassId;
    subItem["o:resource_class"]["@id"] +=  resourceClassId;
    subItem["o:resource_template"]["o:id"] = resourceTemplateId;
    subItem["o:resource_template"]["@id"] +=  resourceTemplateId;

    $('#'+prefix+'_add-button').click(function(e) {
        e.preventDefault();

        if ($('#'+prefix+'_label').val() === "") {
            alert("Bitte Item-Label angben!")
        }
        else {
            // create new element

            //var content = response;
            var elementName =  $('#'+prefix+'_label').val();
            codeValue = codeValue.replace("<label>", elementName);
            //var code = content["o:title"] + "_" + elementName;

            var codeProperty = {
                "type": "literal",
                "property_id": parseInt(codePropertyId),
                "is_public": true,
                "@value": codeValue
            }
            var labelProperty = {
                "type": "literal",
                "property_id": parseInt(labelPropertyId),
                "is_public": true,
                "@value": elementName
            }

            var connectingProperty = {
                "type": "resource:item",
                "property_id": connectingPropertyId,
                "is_public": true,
                "@id": url,
                "value_resource_id": parseInt(itemId)
            }

            subItem[codePropertyName] = [
                codeProperty
            ];
            subItem[labelPropertyName] = [
                labelProperty
            ];
            subItem[connectingPropertyName] = [
                connectingProperty
            ];

            console.log(subItem);

            $.ajax({
                "async": true,
                "crossDomain": true,
                "url": urlAdmin + "/api/items?key_identity=" + keyIdentity + "&key_credential=" + keyCredential,
                "method": "POST",
                "headers": {
                    "content-type": "application/json",
                },
                "data": JSON.stringify(subItem),

            })
            .done(function (response) {
                window.location.href = urlAdmin+"/admin/item/" + response["o:id"] + "/edit";
            });

        }
    });

}
