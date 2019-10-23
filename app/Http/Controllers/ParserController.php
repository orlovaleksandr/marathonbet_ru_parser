<?php

namespace App\Http\Controllers;

use App\Match;
use App\MatchContent;
use Dotenv\Parser;
use Symfony\Component\DomCrawler\Crawler;

class ParserController extends Controller
{
    public function createMatches($pages = 999)
    {
        $allContent = [];

        for ($i = 0; $i < $pages; $i++) {
            $url = 'https://www.marathonbet.ru/su/events.htm?id=11&page=' . $i . '&pageAction=getPage&_=157165416659' . $i;

            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);

            if ($httpCode !== 200) {
                break;
            }

            array_push($allContent, new Crawler(preg_replace('/(\\\)/', '', file_get_contents($url))));
        }

        $allFilteredContent = [];

        foreach ($allContent as $item) {
            $filteredContent = $item->filter(".member-area-content-table")->each(function (Crawler $node) {
                return $node->filter('a')->extract(['_text', 'href']);
            });
            array_push($allFilteredContent, $filteredContent);
        }

        $allMatches = [];

        foreach ($allFilteredContent as $page) {
            foreach ($page as $matches) {
                array_push($allMatches, ['name' => trim($matches[0][0]) . ' : ' . trim($matches[1][0]), 'url' => 'https://www.marathonbet.ru' . trim($matches[0][1])]);
            }
        }

        foreach ($allMatches as $match) {
            Match::create(['name' => $match['name'], 'url' => $match['url']]);
        }


        return redirect()->route('all')->with('status', 'Матчи были успешно спаршены');
    }

    public function show()
    {
        return view('parser.all-matches', ["matches" => Match::paginate(10)]);
    }

    public function getMatch($matchId)
    {

        if (!sizeof(MatchContent::where('match_id', $matchId)->get())) {
            $match = Match::findOrFail($matchId);
            $matchContent = file_get_contents($match->url);
            $matchContent = preg_replace('/(block-market-wrapper\s+hidden)/', 'block-market-wrapper', $matchContent);

            $crawler = new Crawler($matchContent);
            $matchContent = $crawler->filter('.category-container')->html();

            MatchContent::create(['content' => $matchContent, 'match_id' => $matchId]);

            $matchContent = MatchContent::where('match_id', $matchId)->first();

            return view('parser.match', compact('match', 'matchContent'));

        } else {

            $match = Match::findOrFail($matchId);
            $matchContent = MatchContent::where('match_id',  $matchId)->first();

            return view('parser.match', compact('match', 'matchContent'));

        }


    }

}
