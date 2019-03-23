<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 02/03/19
 * Time: 23:59
 */

namespace App\Security;

use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class TaskVoter
 *
 * @package App\Security
 */
class TaskVoter extends Voter
{
    /**
     *
     */
    const ACCESS = 'access';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::ACCESS])) {
            return false;
        }
        if (!$subject instanceof Task) {
            return false;
        }
        return true;
    }

    /**
     * @param string                                                               $attribute
     * @param mixed                                                                $subject
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        $task = $subject;
        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($task, $user);

        }
        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param \App\Entity\Task $task
     * @param \App\Entity\User $user
     *
     * @return bool
     */
    private function canAccess(Task $task, User $user): bool
    {
        return $user === $task->getAuthor();
    }
}