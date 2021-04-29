$( document).ready( function() {
    // api credentials
    var keyIdentity = 'MlGekx6uGkElk0RwDPO1SZdwHtK49pzZ';
    var keyCredential = 'YIWHkP0Kf63FhuT89uUoqDLL5dmPhQPo';

    var url = window.location.href;
    var urlAdmin = url.split("/admin/item")[0];

    $('.mp-delete').click(function(e) {
        e.preventDefault();
        if (confirm("Element '" + e.currentTarget.name + "' löschen")) {
            console.log("delete");

            var targetId = e.currentTarget.href.split("/admin/item/")[1];

            $.ajax({
                "async": true,
                "crossDomain": true,
                "url": urlAdmin + "/api/items/" +  targetId +  "?key_identity=" + keyIdentity + "&key_credential=" + keyCredential,
                "method": "DELETE",
                "headers": {
                    "content-type": "application/json",
                },
            })
            .done(function (response) {
                location.reload(); 
            });

        }
        else {
            console.log("not delete");
        }
    });
});
