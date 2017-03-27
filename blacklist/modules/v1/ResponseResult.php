<?php
namespace app\modules\v1;

/**
 * Description of ResponseRsult
 *
 * @author LXY
 */
class ResponseResult {
    protected $result = ['error' =>null, 'reason'=>null, 'data'=>null];
    const SUCCESS = 0;
    const WARNING = 1;
    const ERROR   = 2;
    
    const REASON = [
        'succ' => '操作成功',
        'sameCompany' => '已有相同公司条目',
        'createUserFault' => '创建用户失败',
        'notFindUser' => '没有找到指定用户',
    ];

    /**
     * 返回结果集
     * @param int $level    //错误等级 成功 提示 错误
     * @param string $reason    //错误原因
     * @param array $data       //返回的数据，如果为null 则返回数据里不包含data
     * @return array
     */
    public function result($level, $reason, $data = null) {
        $this ->result['error'] = $level;
        $this ->result['reason'] = $reason;
        if(isset($data)) {
            $this ->result['data'] = $data;
        } else {
            unset($this ->result['data']);
        }
        return $this ->result;
    }
        
}
