/**
 * Created by suiuu on 15/8/3.
 */

var canvas;
var stage;
var text;
var count=0;
var totalArr;
window.onload= function () {
    canvas=document.getElementById("canvas");
    stage = new createjs.Stage("canvas");
init();
}
function init()
{
    totalArr=Array(
        Array(
            Array(0,0,0,0),
            Array(0,0,0,0),
            Array(1,1,1,1),
            Array(0,0,0,0)
        ),
        Array(
            Array(0,0,0,0),
            Array(1,1,0,0),
            Array(0,0,1,1),
            Array(0,0,0,0)
        ),
        Array(
            Array(0,0,0,0),
            Array(0,1,1,0),
            Array(0,1,1,0),
            Array(0,0,0,0)
        ),
        Array(
            Array(0,1,1,0),
            Array(0,1,0,0),
            Array(0,1,0,0),
            Array(0,0,0,0)
        ),
        Array(
            Array(0,0,0,0),
            Array(0,1,0,0),
            Array(1,1,1,0),
            Array(0,0,0,0)
        )
    );
}
function drawRect(arr)
{

    var Rect= new createjs.Shape();
    Rect.graphics.beginFill("#ff0000");
    Rect.graphics.drawRect(0,0,20,20);
    stage.addChild(Rect);
    stage.update();
}