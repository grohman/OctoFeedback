<?php namespace IDesigning\Feedback;

use IDesigning\Feedback\Models\Channel as ChannelModel;
use IDesigning\Feedback\Models\Feedback as FeedbackModel;
use IDesigning\PostProxy\Interfaces\PostProxyCollector;

/**
 * Class FeedbackPostProxy
 * Collector class for https://github.com/grohman/oc-postproxy
 * @package IDesigning\Feedback
 */
class FeedbackPostProxy implements PostProxyCollector
{

    /** Возвращает название сборщика емейлов
     * @return mixed
     */
    public function getCollectorName()
    {
        return 'Обратная связь';
    }

    /** Возвращает емейлы с именами
     * @param array $scopes
     * @return mixed
     */
    public function collect(Array $scopes = [ ])
    {
        if (isset($scopes[ 0 ]) == false) {
            return [ ];
        }
        $query = FeedbackModel::select('name', 'email', 'phone')->whereNotNull('email')->orWhereNotNull('phone');

        foreach ($scopes as $scope) {
            $fn = $this->getScopes()[ $scope ][ 'scope' ];
            $query = $fn($query);
        }

        $items = $query->get();
        $result = [ ];
        $items->each(function ($item) use (&$result) {
            $email = filter_var($item->email, FILTER_VALIDATE_EMAIL);
            if ($email == false) {
                $email = filter_var($item->phone, FILTER_VALIDATE_EMAIL);
            }
            if ($email != false) {
                if (isset($result[ $email ]) == false) {
                    $name = $item->name;
                    if ($name == null) {
                        $name = explode('@', $email)[ 0 ];
                    }
                    $result[ $email ] = $name;
                }
            }
        });

        return $result;
    }

    /** Возвращает массив условий для поиска
     * @return mixed
     */
    public function getScopes()
    {
        $channels = ChannelModel::select('name', 'id')->lists('name', 'id');
        $result = [ ];
        foreach ($channels as $channelId => $channelName) {
            $result[ $channelId ] = [
                'label' => 'Канал ' . $channelName,
                'scope' => function ($query) use ($channelId) {
                    return $query->where('channel_id', '=', $channelId);
                }
            ];
        }

        return $result;
    }
}