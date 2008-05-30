function debugControl( elemWidth, elemHeight ) {

    if ( !elemWidth )
        elemWidth = 300;
    if ( !elemHeight )
        elemHeight = 300;

    this.dragapproved=false;
    this.ie5=document.all&&document.getElementById;
    this.ns6=document.getElementById&&!document.all;

    var debugWindow = document.createElement( "div" );
    debugWindow.id="debugwindow";
    debugWindow.style.left = (document.body.offsetWidth  - ( elemHeight + 25 ) ) + "px";
    debugWindow.style.top  = "25px";

    var debugTitle = document.createElement( "h3" );
    debugTitle.appendChild( document.createTextNode( "JS Debug Messages" ) );
    debugWindow.appendChild( debugTitle );

    var debugWindowContent = document.createElement( "div" );
    debugWindowContent.id="debugwindowcontent";
    debugWindow.appendChild( debugWindowContent );

    this.tempx=0;
    this.tempy=0;
    this.offsetx=0;
    this.offsety=0;

    var self = this;

    debugWindow.onmousedown = function( e ) {
        self._initDragDrop( e );
    };

    debugWindow.onmousemove = function( e ) {
        self._dragDrop( e );
    };

    debugWindow.onmouseup = debugWindow.onmouseup = function( e ) {
        self._releaseDragDrop( e );
    };

    debugWindow.onselectstart = debugWindow.onselectstart = function( e ) {
        return false;
    };

    document.body.appendChild( debugWindow );
    this.debugWindow = debugWindow;
    this.debugContent = debugWindowContent;


    var styleString = "";
    styleString = styleString + "div#debugwindow {                  ";
    styleString = styleString + "    border: 5px solid #ce5c00;     ";
    styleString = styleString + "    background-color: #eeeeec;     ";
    styleString = styleString + "    height: " + elemHeight + "px;  ";
    styleString = styleString + "    width: " + elemWidth + "px;    ";
    styleString = styleString + "    float: right;                  ";
    styleString = styleString + "    position:fixed;                ";
    styleString = styleString + "    cursor:hand;                   ";
    styleString = styleString + "    left:0px;                      ";
    styleString = styleString + "    top:0px;                       ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";
    styleString = styleString + "div#debugwindowcontent {           ";
    styleString = styleString + "    border: 1px solid #000;        ";
    styleString = styleString + "    overflow-x:none;               ";
    styleString = styleString + "    overflow-y:scroll;             ";
    styleString = styleString + "    height: " + ( elemHeight - 7 ) + "px;  ";
    styleString = styleString + "    position:absolute;             ";
    styleString = styleString + "    width: " + ( elemWidth - 7 ) + "px;    ";
    styleString = styleString + "    padding: 3px;                  ";
    styleString = styleString + "    font-weight: bold;             ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";
    styleString = styleString + "div#debugwindow h3 {               ";
    styleString = styleString + "    color: #babdb6;                ";
    styleString = styleString + "    margin-top: 0px;               ";
    styleString = styleString + "    margin-bottom: 0px;            ";
    styleString = styleString + "    padding-left: 0px;             ";
    styleString = styleString + "    padding-top: 100px;            ";
    styleString = styleString + "    font-size: 2.2em;              ";
    styleString = styleString + "    text-align: center;            ";
    styleString = styleString + "    height: " + elemHeight + "px;  ";
    styleString = styleString + "    width: " + elemWidth + "px;    ";
    styleString = styleString + "    float:left;                    ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";
    styleString = styleString + "span.msg-warn {                    ";
    styleString = styleString + "    color: #c4a000;                ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";
    styleString = styleString + "span.msg-debug {                   ";
    styleString = styleString + "    color: #2e3436;                ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";
    styleString = styleString + "span.msg-error {                   ";
    styleString = styleString + "    color: #a40000;                ";
    styleString = styleString + "}                                  ";
    styleString = styleString + "                                   ";



    var style = document.createElement( "style" );
    style.type = "text/css";

    style.appendChild( document.createTextNode( styleString ) );
    var headTag = document.getElementsByTagName( "head" )[0];
    headTag.appendChild( style );

};

debugControl.prototype._dragDrop = function( e ) {
    if ( this.ie5 && this.dragapproved && event.button==1 ) {
        this.debugWindow.style.left = this.tempx +event.clientX - this.offsetx + "px";
        this.debugWindow.style.top  = this.tempy +event.clientY - this.offsety + "px";
    }
    else if (this.ns6 && this.dragapproved ) {
        this.debugWindow.style.left = this.tempx + e.clientX - this.offsetx + "px";
        this.debugWindow.style.top  = this.tempy + e.clientY - this.offsety + "px";
    }
};

debugControl.prototype._initDragDrop = function( e ) {
    this.offsetx = this.ie5 ? event.clientX : e.clientX;
    this.offsety = this.ie5 ? event.clientY : e.clientY;
    this.tempx = parseInt( this.debugWindow.style.left );
    this.tempy = parseInt( this.debugWindow.style.top );

    this.dragapproved=true;

    this.debugContent.style.display = "none";

    var self = this;
    this.debugWindow.onmousemove = function( e ) {
        self._dragDrop( e );
    };
};

debugControl.prototype._releaseDragDrop = function( e ) {
    this.dragapproved=false;
    this.debugWindow.onmousemove = null;
    this.debugContent.style.display = "";
};

debugControl.prototype._writeDebugMessage = function ( message, cssClass ) {

    var span = document.createElement( "span" );
    span.setAttribute( "class", cssClass );
    span.appendChild( document.createTextNode( message ) );

    this.debugContent.appendChild( span );
    this.debugContent.appendChild( document.createElement( "br" ) );
    this.debugContent.scrollTop += 50;

};

var debugField = false;

function initDebug() {
    if ( debugField == false ) {
        debugField = new debugControl();
    }
}

function debug( message )
{
    if ( debugField ) {
        debugField._writeDebugMessage( message, "msg-debug" );
    }
}

function error( message )
{
    if ( debugField ) {
        debugField._writeDebugMessage( message, "msg-error" );
    }
}

function warning( message )
{
    if ( debugField ) {
        debugField._writeDebugMessage( message, "msg-warn" );
    }
}

