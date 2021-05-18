$( document).ready( function() {

    let litId = "";

    let listElemTexts = {};
    $(".lit-list-elem").map(function () {
        listElemTexts[this.getAttribute("name")] = this.textContent;
    })

    var referenceTemplate = {
        "o:resource_class": {
            "@id": "http://localhost/api/resource_classes/",
            "o:id": 113
        },
        "o:resource_template": {
            "@id": "http://localhost/api/resource_templates/",
            "o:id": 6
        }
    }


    $("#lit-input").on('input', function () {
        let value = $("#lit-input").val().toLowerCase();
        litId = "";
        if (value.length > 3) {
            let show = false
            $(".lit-list-elem").map(function() {
                const text = listElemTexts[this.getAttribute("name")]
                const textLower = text.toLowerCase();
                //const textLower = this.textContent.toLowerCase()

                if (textLower.includes(value)) {

                    const startPos = textLower.indexOf(value);
                    const stopPos = startPos + value.length;
                    let displayText = text.substr(0, stopPos) + "</span>" + text.substr(stopPos)
                    displayText = displayText.substr(0, startPos)+ "<span class='highlight'>"+ displayText.substr(startPos);

                    this.innerHTML = displayText;

                    show = true;
                    this.style.display="block";
                    //this.textContent = listElemTexts[this.getAttribute("name")].repl;
                    //this.className = "lit-list-elem-show";
                    //this.css("display", "block");

                }
                else {
                    this.style.display="none";
                    //this.className = "lit-list-elem";
                    //this.css("display", "none");
                }
            });
            if (show)
                $("#lit-list").css("display", "block");

        }        
        
        else {
            $("#lit-list").css("display", "none");
            $(".lit-list-elem").map(function() {
                
                this.style.display="none";
                //this.className = "lit-list-elem";
            });
        }
    });

    $(".lit-list-elem").click(function (e) {
        console.log(this);
        litId = this.getAttribute("name");
        $("#lit-input").val(listElemTexts[litId]);
        $("#lit-list").css("display", "none");
        $(".lit-list-elem").map(function() {
            this.style.display = "none";
        })
    });

    $("#add-reference-button").click(function (e) {
        e.preventDefault();

        if (litId !== "") {

            var url = window.location.href;
            var urlAdmin = url.split("/admin/item")[0];
            var itemId = url.split("/admin/item/")[1];            

            let connectingPropertyName = $ ('#referenceConnectingProperty').val();
            let connectingPropertyId = $ ('#referenceConnectingPropertyId').val();

            let literaturePropertyName = $ ('#referenceLiteratureProperty').val();
            let litearturePropertyId = $ ('#referenceLiteraturePropertyId').val();

            let codePropertyName = $ ('#referenceCodeProperty').val();
            let codePropertyId = $ ('#referenceCodePropertyId').val();            

            let refPropertyName = $ ('#referenceRefProperty').val();
            let refPropertyId = $ ('#referenceRefPropertyId').val();            

            let resourceClassId = $ ('#referenceResourceClassId').val();
            let resourceTemplateId = $ ('#referenceResourceTemplateId').val();

            let refValue = $('#reference-info').val();
            let litValue = $('#lit-input').val();

            var refItem = JSON.parse(JSON.stringify(referenceTemplate));

            refItem["o:resource_class"]["o:id"] = resourceClassId;
            refItem["o:resource_class"]["@id"] +=  resourceClassId;
            refItem["o:resource_template"]["o:id"] = resourceTemplateId;
            refItem["o:resource_template"]["@id"] +=  resourceTemplateId;

            const codeProperty = {
                "type": "literal",
                "property_id": parseInt(codePropertyId),
                "is_public": true,
                "@value": litValue + " | " + refValue
            }            
            refItem[codePropertyName] = [ codeProperty ];

            const connectingProperty = {
                "type": "resource:item",
                "property_id": connectingPropertyId,
                "is_public": true,
                "@id": url,
                "value_resource_id": parseInt(itemId)
            }
            refItem[connectingPropertyName] = [ connectingProperty ];

            const literatureProperty = {
                "type": "resource:item",
                "property_id": litearturePropertyId,
                "is_public": true,
                "@id": url,
                "value_resource_id": parseInt(litId)
            }
            refItem[literaturePropertyName] = [ literatureProperty ];

            const refProperty = {
                "type": "literal",
                "property_id": parseInt(refPropertyId),
                "is_public": true,
                "@value": refValue
            }
            refItem[refPropertyName] = [ refProperty ];
            console.log(refItem)

            $.ajax({
                "async": true,
                "crossDomain": true,
                "url": urlAdmin + "/api/items?key_identity=" + keyIdentity + "&key_credential=" + keyCredential,
                "method": "POST",
                "headers": {
                    "content-type": "application/json",
                },
                "data": JSON.stringify(refItem),

            })
            .done(function (response) {
                //window.location.href = urlAdmin+"/admin/item/" + response["o:id"] + "/edit";
                location.reload(); 
            });
        }
    });

});