$( document).ready( function() {
    $( ".title" ).each(function () {
        const text = this.innerHTML
        this.innerHTML = text.replaceAll("_", "&nbsp;&nbsp;&nbsp;&nbsp; ")
        console.log(this);
    })

    $(".alternate").each(function () {
        const text = this.innerHTML
        this.innerHTML = text.replaceAll("_", "<br />")
        console.log(this)
    })

    $(".property").each(function () {
        const text = this.innerHTML
        this.innerHTML = text.replaceAll("_", "<br />")
        console.log(this)
    })    

    $(".resource-name").each(function () {
        const text = this.innerHTML
        this.innerHTML = text.replaceAll("_", "<br />")
        console.log(this)
    })        

    $(".collapse").each(function () {
        this.className="expand"
    })
})
