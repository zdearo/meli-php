<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing questions in the Mercado Libre API.
 */
class QuestionService
{
    /**
     * Search questions with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return array<string, mixed> The search results
     *
     * @throws ApiException If the request fails
     */
    public function search(array $filters = []): array
    {
        $filters['api_version'] = 4;
        
        return ApiRequest::get('questions/search')
            ->withQuery($filters)
            ->send()
            ->json();
    }

    /**
     * Get questions by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The seller questions
     *
     * @throws ApiException If the request fails
     */
    public function getBySeller(int $sellerId, array $filters = []): array
    {
        $filters['seller_id'] = $sellerId;
        return $this->search($filters);
    }

    /**
     * Get questions for a specific item.
     *
     * @param  string  $itemId  The item ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The item questions
     *
     * @throws ApiException If the request fails
     */
    public function getByItem(string $itemId, array $filters = []): array
    {
        $filters['item'] = $itemId;
        return $this->search($filters);
    }

    /**
     * Get questions by user (who asked the question).
     *
     * @param  int  $userId  The user ID who asked the question
     * @param  string  $itemId  The item ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The user questions
     *
     * @throws ApiException If the request fails
     */
    public function getByUser(int $userId, string $itemId, array $filters = []): array
    {
        $filters['from'] = $userId;
        $filters['item'] = $itemId;
        return $this->search($filters);
    }

    /**
     * Get question by ID.
     *
     * @param  int  $questionId  The question ID
     * @return array<string, mixed> The question details
     *
     * @throws ApiException If the request fails
     */
    public function get(int $questionId): array
    {
        return ApiRequest::get("questions/{$questionId}")
            ->withQuery(['api_version' => 4])
            ->send()
            ->json();
    }

    /**
     * Create a new question for an item.
     *
     * @param  string  $itemId  The item ID
     * @param  string  $text  The question text (max 2000 characters)
     * @return array<string, mixed> The created question
     *
     * @throws ApiException If the request fails
     */
    public function create(string $itemId, string $text): array
    {
        return ApiRequest::post('questions')
            ->withBody([
                'item_id' => $itemId,
                'text' => $text,
            ])
            ->send()
            ->json();
    }

    /**
     * Answer a question.
     *
     * @param  int  $questionId  The question ID
     * @param  string  $text  The answer text (max 2000 characters)
     * @return array<string, mixed> The created answer
     *
     * @throws ApiException If the request fails
     */
    public function answer(int $questionId, string $text): array
    {
        return ApiRequest::post('answers')
            ->withBody([
                'question_id' => $questionId,
                'text' => $text,
            ])
            ->send()
            ->json();
    }

    /**
     * Delete a question.
     *
     * @param  int  $questionId  The question ID
     * @return Response The response
     *
     * @throws ApiException If the request fails
     */
    public function delete(int $questionId): Response
    {
        return ApiRequest::delete("questions/{$questionId}")
            ->send();
    }

    /**
     * Get unanswered questions by seller.
     *
     * @param  int  $sellerId  The seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The unanswered questions
     *
     * @throws ApiException If the request fails
     */
    public function getUnansweredBySeller(int $sellerId, array $filters = []): array
    {
        $filters['status'] = 'UNANSWERED';
        return $this->getBySeller($sellerId, $filters);
    }

    /**
     * Get answered questions by seller.
     *
     * @param  int  $sellerId  The seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The answered questions
     *
     * @throws ApiException If the request fails
     */
    public function getAnsweredBySeller(int $sellerId, array $filters = []): array
    {
        $filters['status'] = 'ANSWERED';
        return $this->getBySeller($sellerId, $filters);
    }

    /**
     * Get questions with specific status.
     *
     * @param  string  $status  The question status (ANSWERED, UNANSWERED, BANNED, etc.)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The filtered questions
     *
     * @throws ApiException If the request fails
     */
    public function getByStatus(string $status, array $filters = []): array
    {
        $filters['status'] = $status;
        return $this->search($filters);
    }

    /**
     * Search questions with sorting.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  array<string>  $sortFields  Fields to sort by (item_id, seller_id, from_id, date_created)
     * @param  string  $sortType  Sort type (ASC, DESC)
     * @return array<string, mixed> The sorted results
     *
     * @throws ApiException If the request fails
     */
    public function searchSorted(array $filters = [], array $sortFields = [], string $sortType = 'ASC'): array
    {
        if (!empty($sortFields)) {
            $filters['sort_fields'] = implode(',', $sortFields);
            $filters['sort_types'] = $sortType;
        }
        
        return $this->search($filters);
    }

    /**
     * Search questions with pagination.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Number of results per page
     * @param  int  $offset  Results offset
     * @return array<string, mixed> The paginated results
     *
     * @throws ApiException If the request fails
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;
        
        return $this->search($filters);
    }

    /**
     * Get user's question response time metrics.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The response time metrics
     *
     * @throws ApiException If the request fails
     */
    public function getResponseTime(int $userId): array
    {
        return ApiRequest::get("users/{$userId}/questions/response_time")
            ->send()
            ->json();
    }

    /**
     * Hide questions from listing.
     *
     * @param  array<int>  $questionIds  Array of question IDs to hide
     * @return array<string, mixed> The response
     *
     * @throws ApiException If the request fails
     */
    public function hideQuestions(array $questionIds): array
    {
        return ApiRequest::post('my/questions/hidden')
            ->withBody(['question_ids' => $questionIds])
            ->send()
            ->json();
    }

    /**
     * Get banned questions.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The banned questions
     *
     * @throws ApiException If the request fails
     */
    public function getBannedQuestions(array $filters = []): array
    {
        return $this->getByStatus('BANNED', $filters);
    }

    /**
     * Get questions under review.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The questions under review
     *
     * @throws ApiException If the request fails
     */
    public function getQuestionsUnderReview(array $filters = []): array
    {
        return $this->getByStatus('UNDER_REVIEW', $filters);
    }

    /**
     * Get closed unanswered questions.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The closed unanswered questions
     *
     * @throws ApiException If the request fails
     */
    public function getClosedUnansweredQuestions(array $filters = []): array
    {
        return $this->getByStatus('CLOSED_UNANSWERED', $filters);
    }
}