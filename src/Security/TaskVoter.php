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
    const EDIT = 'edit';

    /**
     *
     */
    const DELETE = 'delete';

    /**
     *
     */
    const DONE = 'done';

    /**
     *
     */
    const SEND = 'send';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE, self::DONE, self::SEND])) {
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
            case self::EDIT:
                return $this->canEdit($task, $user);
            case self::DELETE:
                return $this->canDelete($task, $user);
            case self::DONE:
                return $this->canDone($task, $user);
            case self::SEND:
                return $this->canSend($task, $user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param \App\Entity\Task $task
     * @param \App\Entity\User $user
     *
     * @return bool
     */
    private function canEdit(Task $task, User $user): bool
    {
        return $user === $task->getAuthor();
    }

    /**
     * @param \App\Entity\Task $task
     * @param \App\Entity\User $user
     *
     * @return bool
     */
    private function canDelete(Task $task, User $user)
    {
        if ($task->getAuthor() === null & $user->getRoles() === ['ROLE_ADMIN'] or $user === $task->getAuthor()) {
            return true;
        }
    }

    /**
     * @param \App\Entity\Task $task
     * @param \App\Entity\User $user
     *
     * @return bool
     */
    private function canDone(Task $task, User $user): bool
    {
        return $user === $task->getAuthor();
    }

    /**
     * @param \App\Entity\Task $task
     * @param \App\Entity\User $user
     *
     * @return bool
     */
    private function canSend(Task $task, User $user): bool
    {
        return $user === $task->getAuthor();
    }
}