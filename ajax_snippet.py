##in the new brach


obj = 'btn_new_dungeon'
style = 'get' # get or ajax
evnt = 'click' # click  change  mouseup


if style == 'get':
    print('$("#{}").{}(function() {{'.format(obj,evnt))
    print('    HourGlass(true)')
    print('    $.get(base_url + \"CONTROLLER/METHOD" + $("#{}").val(),'.format(obj))
    print('        function(data) {')
    print('            HourGlass(false)')
    print('            ShowAlert("say something","title","bottom")')
    print('            //var o = JSON.parse(data)')
    print('        } // callback')
    print('    ) // get')
    print('}}) // {}.{}'.format(obj, evnt))
    

    
    
    