   <!-- Status History -->
   <div class="card">
                    <h2>
                        <i class="fas fa-history"></i>
                        Status History
                    </h2>
                    
                    @if($inquiry->history && $inquiry->history->count() > 0)
                    <div class="timeline">                        @foreach($inquiry->history->sortByDesc('timestamp') as $history)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <span class="timeline-status">
                                        <strong>
                                            {{ $history->new_status ?? 'Unknown' }}
                                        </strong>
                                    </span>
                                    <span class="timeline-date">{{ $history->timestamp->format('M d, Y H:i') }}</span>
                                </div>
                                @if($history->comments)
                                <div class="timeline-comment">{{ $history->comments }}</div>
                                @endif
                                @if($userRole === 'admin' || $userRole === 'agency')
                                <div class="timeline-user">
                                    <small class="text-muted">
                                        by {{ $history->changedBy->name ?? 'System' }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">No status changes recorded yet.</p>
                    @endif

                    <!-- Verification Process Details -->
                    @if($inquiry->verificationProcesses && $inquiry->verificationProcesses->count() > 0)
                        <div style="margin-top: 2rem;">
                            <h3 style="color: #1e293b; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-clipboard-list"></i>
                                Investigation Details
                            </h3>
                            
                            @foreach($inquiry->verificationProcesses as $verification)
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem;">
                                    @if($verification->agency)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #1e293b;">Assigned Agency:</strong>
                                            <span style="color: #475569;">{{ $verification->agency->agency_name ?? $verification->agency->name ?? 'Unknown Agency' }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($verification->assigned_at)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #1e293b;">Assigned Date:</strong>
                                            <span style="color: #475569;">{{ $verification->assigned_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endif

                                    @if($verification->notes)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #1e293b;">Investigation Notes:</strong>
                                            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem; margin-top: 0.5rem; color: #475569; white-space: pre-wrap;">{{ $verification->notes }}</div>
                                        </div>
                                    @endif

                                    @if($verification->explanation_text)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #1e293b;">Explanation:</strong>
                                            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem; margin-top: 0.5rem; color: #475569; white-space: pre-wrap;">{{ $verification->explanation_text }}</div>
                                        </div>
                                    @endif

                                    @if($verification->rejection_reason)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #dc2626;">Rejection Reason:</strong>
                                            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 1rem; margin-top: 0.5rem; color: #991b1b; white-space: pre-wrap;">{{ $verification->rejection_reason }}</div>
                                        </div>
                                    @endif

                                    @if($verification->priority)
                                        <div style="margin-bottom: 1rem;">
                                            <strong style="color: #1e293b;">Priority:</strong>
                                            <span style="color: #475569;">{{ $verification->priority }}</span>
                                        </div>
                                    @endif

                                    @if($verification->confirmed_at)
                                        <div>
                                            <strong style="color: #1e293b;">Confirmed Date:</strong>
                                            <span style="color: #475569;">{{ $verification->confirmed_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>