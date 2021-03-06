<?php

namespace juniorb2ss\DeathByCaptcha\Interfaces;

use juniorb2ss\DeathByCaptcha\Interfaces\StatusInterface;
use juniorb2ss\DeathByCaptcha\Interfaces\AccountInterface;
use juniorb2ss\DeathByCaptcha\Interfaces\ResolveInterface;

interface DeathByCaptchaInterface
{
    /**
     * Recupera informações sobre a conta
     * @return AccountInterface
     */
    public function account(): AccountInterface;

    /**
     * Recupera status do serviço
     * @return StatusInterface [description]
     */
    public function status(): StatusInterface;

    /**
     * Envia o captcha pro serviço ou recupera o resultado
     * de um captcha previamente enviado
     * @param  $mix $captcha ID do captcha ou imagem
     * @return ResolveInterface
     */
    public function resolve($mix): ResolveInterface;

    /**
     * Envia o captcha pro serviço ou recupera o resultado
     * de um captcha previamente enviado
     * @param  $mix $captcha ID do captcha or site-key
     * @param  $url $url url
     * @return ResolveInterface
     */
    public function resolveV2(string $mix, string $url = null): ResolveInterface;

    /**
     * Informa ao serviço que o captcha recuperado
     * é inválido.
     * @param  int    $id
     * @return ReportInterface
     */
    public function report(int $id): ReportInterface;
}
