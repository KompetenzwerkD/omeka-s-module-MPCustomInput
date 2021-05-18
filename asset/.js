$( document).ready( function() {

    $("#lit-input").on('input', function () {
        let value = $("#lit-input").val().toLowerCase();
        if (value.length > 3) {
            $(".lit-list-elem").map(function() {
                if (this.textContent.toLowerCase().includes(value)) {

                    this.style.display="block";
                    //this.className = "lit-list-elem-show";
                    //this.css("display", "block");

                }
                else {
                    this.style.display="none";
                    //this.className = "lit-list-elem";
                    //this.css("display", "none");
                }
            });
        }
        else {
            $(".lit-list-elem").map(function() {
                this.style.display="none";
                //this.className = "lit-list-elem";
            });
        }
    });

    $(".lit-list-elem").click(function (e) {
        console.log(e);
        $("#lit-input").value = e.textContent;
        $(".lit-lit-elem").map(function() {
            this.style.display = "none";
        })
    });

});