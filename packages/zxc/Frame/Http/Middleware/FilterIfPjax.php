<?php
namespace Zxc\Frame\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Spatie\Pjax\Middleware\FilterIfPjax as BaseClass;

class FilterIfPjax extends BaseClass
{

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!$request->pjax() || $response->isRedirection()) {
            return $response;
        }

        //为script中的闭合标签做变换保护
        $response->setContent($this->convScript($response->getContent(),'<',' &lt '));

        $this->filterResponse($response, $request->header('X-PJAX-Container'))
            ->setUriHeader($response, $request)
            ->setVersionHeader($response, $request);

        //重新修改，并对转义的汉字解码
        $response->setContent($this->convRes($response->getContent()));

        return $response;
    }

    protected function convScript($html,$search='<', $replace=' &lt '){
        $arr=preg_split('/<(?=script[ >])/i',$html);
        if(count($arr)>1){
            $html=array_shift($arr);
            foreach($arr as $a){
                $arr2=explode('</script>',$a,2);
                $text=str_replace($search,$replace,$arr2[0]);
                $html=$html.'<'.$text.'</script>'.$arr2[1];
            }
        }
        return $html;
    }

    protected function convRes($html){
        //str_replace('"','&quot',$html)
        $reshtml=$this->convScript($html,' &lt ','<');
        return html_entity_decode($reshtml);
    }

}