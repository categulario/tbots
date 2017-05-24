<?php

namespace App\Bots;

use Illuminate\Http\Request;

class Eqxbot extends Bot
{
    public function hello(Request $request)
    {
        return [
            'name' => 'Eqxbot',
        ];
    }

    private function renderLatex($query)
    {
        $texfile = tmpfile();

        fwrite($texfile, '
\\nonstopmode
\documentclass{minimal}
\usepackage{amsmath}
\usepackage{amssymb}
\usepackage[active,tightpage]{preview}
\usepackage{transparent}
\begin{document}
\begin{preview} $\displaystyle '.$query.'$ \end{preview}
\end{document}');

        $texfilename = stream_get_meta_data($texfile)['uri'];
        $directory = dirname($texfilename);

        $returncode = 0;
        $output = [];

        exec("/usr/bin/pdflatex -output-directory=$directory -interaction=nonstopmode $texfilename", $output, $returncode);

        if ($returncode !== 0) {
            return 'bad.png';
        }

        $imagedata = shell_exec("/usr/bin/convert -density 300 -quality 100 -flatten pdf:$texfilename.pdf png:-");

        $pngname = basename($texfilename).'.png';

        file_put_contents(base_path().'/public/latex/'.$pngname, $imagedata);

        return $pngname;
    }

    public function inlineQuery(Request $request)
    {
        $query = $request->input('inline_query.query');

        $result = $this->renderLatex($query);

        return [
            'method' => 'answerInlineQuery',

            'inline_query_id' => $request->input('inline_query.id'),
            'cache_time' => 300,
            'results' => [
                [
                    'type' => 'photo',
                    'id' => $result,
                    'photo_url' => 'https://tbots.categulario.tk/latex/'.$result,
                    'thumb_url' => 'https://tbots.categulario.tk/latex/'.$result,
                ],
            ],
        ];
    }

    public function message(Request $request)
    {
        $query = $request->input('message.text');

        $result = $this->renderLatex($query);

        return [
            'method'       => 'sendPhoto',
            'chat_id'      => $request->input('message.chat.id'),
            'photo'        => 'https://tbots.categulario.tk/latex/'.$result,
        ];
    }
}
