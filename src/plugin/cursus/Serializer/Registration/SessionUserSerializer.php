<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CursusBundle\Serializer\Registration;

use Claroline\AppBundle\API\Options;
use Claroline\AppBundle\API\Serializer\SerializerTrait;
use Claroline\CommunityBundle\Serializer\UserSerializer;
use Claroline\CursusBundle\Entity\Registration\AbstractUserRegistration;
use Claroline\CursusBundle\Entity\Registration\SessionUser;
use Claroline\CursusBundle\Serializer\SessionSerializer;

class SessionUserSerializer extends AbstractUserSerializer
{
    use SerializerTrait;

    /** @var SessionSerializer */
    private $sessionSerializer;

    public function __construct(UserSerializer $userSerializer, SessionSerializer $sessionSerializer)
    {
        parent::__construct($userSerializer);

        $this->sessionSerializer = $sessionSerializer;
    }

    public function getClass()
    {
        return SessionUser::class;
    }

    /**
     * @param SessionUser $sessionUser
     */
    public function serialize(AbstractUserRegistration $sessionUser, array $options = []): array
    {
        return array_merge(parent::serialize($sessionUser, $options), [
            'session' => $this->sessionSerializer->serialize($sessionUser->getSession(), [Options::SERIALIZE_MINIMAL]),
        ]);
    }
}
