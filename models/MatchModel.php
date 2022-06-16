<?php if (!defined('TOKEN')) { include '../views/404.php'; exit(); } ?>
<?php

include_once('lib/_init.php');

class MatchModel {
    // descending by default
    private static function sortMatchesByDate(&$array, $inverse = false) {
        usort($array, function($a, $b) use ($inverse){
            if( $a['date'] == $b['date'] )
                return 0;
            else if($a['date'] > $b['date'])
                return $inverse ? 1 : -1;
            else
                return $inverse ? -1 : 1;
        });
    }

    public static function getLatestResultsMatchesBySection() {
        $matches =  MatchStorage::getContext()->findMany(function ($match){
            return $match["home"]["score"] !== NULL && $match["away"]["score"] !== NULL;
        });
        self::sortMatchesByDate($matches);

        return $matches;
    }

    /* returns the matches PLAYED or WILL BE by a team sorted by date descending.  */
    public static function getMatchById($matchId) {
        return MatchStorage::getContext()->findById($matchId);
    }

    public static function updateMatchById($matchId, $homeScore, $awayScore, $date) {
        $prev = MatchStorage::getContext()->findById($matchId);
        $prev["date"] = $date;
        $prev["home"]["score"] = $homeScore;
        $prev["away"]["score"] = $awayScore;
        MatchStorage::getContext()->update($matchId, $prev);
    }

    public static function deleteMatchById($matchId) {
        MatchStorage::getContext()->delete($matchId);
    }

    /* returns the matches PLAYED or WILL BE by a team sorted by date descending.  */
    public static function getMatchesByTeamId($teamId, $inverse = false) {
        $matches =  MatchStorage::getContext()->findMany(function ($match) use ($teamId) {
            return $match["home"]["id"] == $teamId || $match["away"]["id"] == $teamId;
        });

        self::sortMatchesByDate($matches, $inverse);

        return $matches;
    }

    /* returns the matches PLAYED by a team sorted by date descending.  */
    public static function getPlayedMatches($teamId, $inverse = false) {
        $matches =  MatchStorage::getContext()->findMany(function ($match) use ($teamId) {
            return ( $match["home"]["id"] == $teamId || $match["away"]["id"] == $teamId )
                && ( $match["home"]["score"] !== NULL && $match["away"]["score"] !== NULL );
        });

        self::sortMatchesByDate($matches, $inverse);

        return $matches;
    }

    /* returns the matches WILL BE by a team sorted by date descending.  */
    public static function getUpcomingMatches($teamId, $inverse = false) {
        $matches =  MatchStorage::getContext()->findMany(function ($match) use ($teamId) {
            return ( $match["home"]["id"] == $teamId || $match["away"]["id"] == $teamId )
                && ( $match["home"]["score"] === NULL && $match["away"]["score"] === NULL );
        });

        self::sortMatchesByDate($matches, $inverse);

        return $matches;
    }

    public static function getRecentlyPlayedDate($teamId) {
        $matches = self::getMatchesByTeamId($teamId);
        foreach ($matches as $m) {
            if ($m["home"]["score"] !== NULL && $m["away"]["score"] !== NULL) {
                return $m["date"];
            }
        }
        return NULL;
    }
}
