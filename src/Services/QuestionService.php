<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\Question;
use Zdearo\Meli\DTO\SearchResult;
use Zdearo\Meli\Support\ApiRequest;
use Zdearo\Meli\Support\LazyPaginator;

/**
 * Service for managing questions in the Mercado Libre API.
 */
class QuestionService
{
    /**
     * Search questions with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return SearchResult The search results containing Question DTOs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function search(array $filters = []): SearchResult
    {
        $filters['api_version'] = 4;

        $data = ApiRequest::get('questions/search')
            ->withQuery($filters)
            ->send()
            ->json();

        return new SearchResult(
            results: array_map(Question::fromArray(...), $data['questions'] ?? []),
            total: $data['total'] ?? 0,
            offset: $data['offset'] ?? 0,
            limit: $data['limit'] ?? 50,
        );
    }

    /**
     * Lazily iterate over all questions matching the filters.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Results per page
     * @return LazyPaginator Paginator yielding Question DTOs
     */
    public function searchAll(array $filters = [], int $limit = 50): LazyPaginator
    {
        return new LazyPaginator(
            fetcher: function (int $offset, int $limit) use ($filters) {
                $filters['limit'] = $limit;
                $filters['offset'] = $offset;

                return $this->search($filters);
            },
            limit: $limit,
        );
    }

    /**
     * Get questions by seller ID.
     */
    public function getBySeller(int $sellerId, array $filters = []): SearchResult
    {
        $filters['seller_id'] = $sellerId;

        return $this->search($filters);
    }

    /**
     * Get questions for a specific item.
     */
    public function getByItem(string $itemId, array $filters = []): SearchResult
    {
        $filters['item'] = $itemId;

        return $this->search($filters);
    }

    /**
     * Get questions by user (who asked the question).
     */
    public function getByUser(int $userId, string $itemId, array $filters = []): SearchResult
    {
        $filters['from'] = $userId;
        $filters['item'] = $itemId;

        return $this->search($filters);
    }

    /**
     * Get question by ID.
     *
     * @param  int  $questionId  The question ID
     * @return Question The question details
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(int $questionId): Question
    {
        $data = ApiRequest::get("questions/{$questionId}")
            ->withQuery(['api_version' => 4])
            ->send()
            ->json();

        return Question::fromArray($data);
    }

    /**
     * Create a new question for an item.
     *
     * @param  string  $itemId  The item ID
     * @param  string  $text  The question text (max 2000 characters)
     * @return Question The created question
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function create(string $itemId, string $text): Question
    {
        $data = ApiRequest::post('questions')
            ->withBody([
                'item_id' => $itemId,
                'text' => $text,
            ])
            ->send()
            ->json();

        return Question::fromArray($data);
    }

    /**
     * Answer a question.
     *
     * @param  int  $questionId  The question ID
     * @param  string  $text  The answer text (max 2000 characters)
     * @return Response The created answer
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function answer(int $questionId, string $text): Response
    {
        return ApiRequest::post('answers')
            ->withBody([
                'question_id' => $questionId,
                'text' => $text,
            ])
            ->send();
    }

    /**
     * Delete a question.
     *
     * @param  int  $questionId  The question ID
     * @return Response The response
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function delete(int $questionId): Response
    {
        return ApiRequest::delete("questions/{$questionId}")
            ->send();
    }

    /**
     * Get unanswered questions by seller.
     */
    public function getUnansweredBySeller(int $sellerId, array $filters = []): SearchResult
    {
        $filters['status'] = 'UNANSWERED';

        return $this->getBySeller($sellerId, $filters);
    }

    /**
     * Get answered questions by seller.
     */
    public function getAnsweredBySeller(int $sellerId, array $filters = []): SearchResult
    {
        $filters['status'] = 'ANSWERED';

        return $this->getBySeller($sellerId, $filters);
    }

    /**
     * Get questions with specific status.
     *
     * @param  string  $status  The question status (ANSWERED, UNANSWERED, BANNED, etc.)
     */
    public function getByStatus(string $status, array $filters = []): SearchResult
    {
        $filters['status'] = $status;

        return $this->search($filters);
    }

    /**
     * Search questions with sorting.
     */
    public function searchSorted(array $filters = [], array $sortFields = [], string $sortType = 'ASC'): SearchResult
    {
        if (! empty($sortFields)) {
            $filters['sort_fields'] = implode(',', $sortFields);
            $filters['sort_types'] = $sortType;
        }

        return $this->search($filters);
    }

    /**
     * Search questions with pagination.
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): SearchResult
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;

        return $this->search($filters);
    }

    /**
     * Get user's question response time metrics.
     *
     * @param  int  $userId  The user ID
     * @return Response The response time metrics
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getResponseTime(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}/questions/response_time")
            ->send();
    }

    /**
     * Hide questions from listing.
     *
     * @param  array<int>  $questionIds  Array of question IDs to hide
     * @return Response The response
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function hideQuestions(array $questionIds): Response
    {
        return ApiRequest::post('my/questions/hidden')
            ->withBody(['question_ids' => $questionIds])
            ->send();
    }

    /**
     * Get banned questions.
     */
    public function getBannedQuestions(array $filters = []): SearchResult
    {
        return $this->getByStatus('BANNED', $filters);
    }

    /**
     * Get questions under review.
     */
    public function getQuestionsUnderReview(array $filters = []): SearchResult
    {
        return $this->getByStatus('UNDER_REVIEW', $filters);
    }

    /**
     * Get closed unanswered questions.
     */
    public function getClosedUnansweredQuestions(array $filters = []): SearchResult
    {
        return $this->getByStatus('CLOSED_UNANSWERED', $filters);
    }
}
